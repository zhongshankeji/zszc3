threecolumn = {
	"selector": ".threecolumn",
	validate: function(obj){
		var result = $(obj).find('input').val() != "";
		var title = $(obj).find('.qdescription').find('td');
		if (!result)	{
			title.css('color', 'red');
		}
		else	{
			title.css('color', '#232323');
		}
		return result;
	},
	randomize: function(obj){
		return;
	},
	loadDefaults: function(obj, defaultValue){
		var container = $(obj).find('tbody:eq(1)');
		container.find('tr').each(function(index){
			$(this).find('td:last').css('border-right', 0);
		})
		$(obj).find('th:last').css('border-right', 0);
		container.find('tr:first').addClass('firstrow');
		container.find('tr:last').find('td').css('border-bottom', 0);
		
		for (name in defaultValue)	{
			$(obj).find('input[name=' + name + ']').each(function(index){
				var itemValue = defaultValue[name];
				$(this).val(itemValue);
				
				var sequence = itemValue.split(';');
				var items = $(obj).find('.column_b_item');
				
				for (var s in sequence)	{
					if (sequence[s] == "") return;
					items.each(function(index, obj)	{
						if ($(obj).attr('itemvalue') == sequence[s])	{
							var display = $(obj).css('display');
							$(obj).css('display', 'block');
							$(obj).click();
							$(obj).css('display', display);
						}	
					});
				}
			});
		}
	},
	localize: function(obj, localizeValue) {
		
	}
}
// survey.page.handlers.push(threecolumn);

    ITEMS_LIMIT = 30

	function groupColumnC(p)	{
		var cc = p.find('.column_c');
        var summary_text = p.parentsUntil('.qcontent').find('.summarytext')
        var items = cc.find('.column_c_item');
        summary_text.text(items.length + ' / ' + ITEMS_LIMIT)

        if (p.attr('mode') == 'group') {
            var groups = {}
            items.each(function(index, obj){
                var objGroup = $(obj).attr('group');
                if (groups[objGroup] == undefined)
                    groups[objGroup] = [];
                groups[objGroup].push(obj);
                $(obj).detach();
            });
            cc.empty();
            var itemValue = "";
            for (group in groups)	{
                var groupDiv = $('<div class="column_c_group">');
                groupDiv.appendTo(cc);
                groupDiv.append($('<p>' + group +'</p>'));
                var items = groups[group];
                for (i in items)	{
                    $(items[i]).appendTo(groupDiv);
                    itemValue = itemValue + $(items[i]).attr('itemvalue') + ";"
                }
            }
            var input = cc.parentsUntil('.threecolumn').find('input');
            input.val(itemValue);
        }
        else    {
            var ul = $('<ul>')
            items.each(function(index, obj){
                var li = $('<li>');
                li.addClass('ui-sortable-state-default')
                $(obj).appendTo(li);
                li.appendTo(ul)
            });
            cc.empty();
            ul.appendTo(cc);

            ul.sortable({
                update: function()   {
                    updateSkills();
                }
            });
            ul.disableSelection();
        }

        updateColumnB(p);
    }

    function updateColumnB(obj)    {
        var p = obj.parentsUntil('.threecolumn').parent();
        var items = p.find('.column_b_item');
        var selected = p.find('.column_c_item');

        items.each(function(index, obj_b){
            var is_selected = false;
            selected.each(function(index, obj_c)    {
                if ($(obj_c).attr('itemvalue') == $(obj_b).attr('itemvalue')) {
                    is_selected = true;
                }
            });

            if (is_selected)    {
                $(obj_b).addClass('column_b_selected')
            }
            else    {
                $(obj_b).removeClass('column_b_selected')
            }
        });
    }
	
	function updateSkills() {
	    var skills = [];
	    $('.threecolumn').find('.column_c_item').each(function(index, obj){
			skills.push([$("#id_skill_family").val(), $(obj).attr("group"), $(obj).attr("itemvalue")].join("||"));
		});
		var skills_string = skills.join("$$");
		$("#id_skills").val(skills_string);
	}
	
	function selectColumnC()	{
		var p = $(this).parent();
		var pp = p.parentsUntil('.threecolumn').parent();
        p.detach();
		groupColumnC(pp);
		updateSkills();
	}	
	
	function selectColumnA()	{
		var txt = $(this).attr('group');
		var items = $(this).parentsUntil('.threecolumn').find('.column_b_item');
		items.each(function(index, obj){
			var group = $(obj).attr('group');
			if (group == txt)	{
				$(obj).show();
			}
			else 	{
				$(obj).hide();
			}
		});
		$('.column_a_selected').removeClass('column_a_selected');
		$(this).addClass('column_a_selected');
	}

    function showAlert(trigger)    {
        var popup = $('.bubbledalert-popup').css('opacity', 0);
        var distance = 20;
        var time = 250;
        var hideDelay = 2000;
        var hideDelayTimer;
        popup.css({
            top: trigger.position().top - 400 +  "px",
            left: trigger.position().left + "px",
            display: 'block' // brings the popup back in to view
        }).animate({
                top: '-=' + distance + 'px', opacity: 1
            }, time, 'swing', function() {
                hideDelayTimer = setTimeout(function () {
                    hideDelayTimer = null;
                    popup.animate({
                        top: '+=' + 0 + 'px',
                        opacity: 0
                    }, time, 'swing', function () {
                        // once the animate is complete, set the tracker variables
                        shown = false;
                        // hide the popup entirely after the effect (opacity alone doesn't do the job)
                        popup.css('display', 'none');
                    });
                }, hideDelay);
            }
        );
    }
	
	function selectColumnB(e, ele)	{
    if (ele) {
      var $ele = $(ele);
    } else {
      var $ele = $(this);
    } 
    var columnC = $ele.parentsUntil('.threecolumn').find('.column_c');
    var threeColumnParent = $ele.parentsUntil('.threecolumn').parent();
    if (!ele) {
      var val = $ele.attr('itemvalue');
      var items = columnC.find('.column_c_item');
      if (items.length >= ITEMS_LIMIT)   {
          showAlert($ele);
          return;
      }
		  var exist = columnC.find('.column_c_item[itemvalue="'+val+'"]').length ? true : false;
      /*
      items.each(function(index, obj)	{
        if ($(obj).attr('itemvalue') == val)	{
          exist = true;
        }
      });
      */
    } else{
      var exist = false;
    }
		if (!exist)	{
			var newItem = $ele.clone();
      newItem.find('.recommend').remove();
		  var txt = $.trim(newItem.text());
      //newItem.attr('title', txt)
      newItem.text('');
		  if (txt.length > 22) {
		    txt = txt.substr(0, 19) + '...';
		    // newItem.text(txt);
			}
            var pItem = $('<p>');
            pItem.text(txt);
            pItem.appendTo(newItem);
			newItem.removeClass('column_b_item column_b_selected').addClass('column_c_item');
			var closeItem = $('<a>X</a>')
			closeItem.appendTo(newItem);
      columnC.append(newItem);
			closeItem.click(selectColumnC);
		  if (!ele) groupColumnC(threeColumnParent);

		}
		if (!ele) updateSkills();
	}

function three(){
    $(".threecolumn").each(function(index, obj)	{
        var items = $(obj).find('.column_b_item');
        var groups = {};
        items.each(function(index, obj){
            groups[$(obj).attr('group')] = 1;
            if ($(obj).attr('recommend') == 1)  {
                var outer = $(obj).wrap('<div></div>')
                var star = $('<div></div>')
                star.addClass('recommend')
                outer.prepend(star);
            }
            $(obj).attr('title', $(obj).attr('description'));
			$(obj).click(selectColumnB);
		});
		
		var ca = $(obj).find('.column_a');
		var first = true;
		for (group in groups){
			var caItem = $('<div>');
			caItem.addClass('column_a_item');
			caItem.text(group);
			caItem.attr('group', group);
			caItem.append($('<div class="rightarrow"/>'));
			caItem.appendTo(ca);
			caItem.click(selectColumnA);
			if (first){
				first = false;
				caItem.click();
			}
		}
	});

    $('.bubbledalert').each(function () {
        // options
        var distance = 20;
        var time = 250;
        var hideDelay = 100;
        var hideDelayTimer = null;

        // tracker
        var beingShown = false;
        var shown = false;
        var popup = $('.bubbledalert-popup').css('opacity', 0);

        // set the mouseover and mouseout on both element
        popup.click(function () {
            // reset the timer if we get fired again - avoids double animations
            if (hideDelayTimer) clearTimeout(hideDelayTimer);
            // store the timer so that it can be cleared in the mouseover if required
            hideDelayTimer = setTimeout(function () {
                hideDelayTimer = null;
                popup.animate({
                    top: '+=' + distance + 'px',
                    opacity: 0
                }, time, 'swing', function () {
                    // once the animate is complete, set the tracker variables
                    shown = false;
                    // hide the popup entirely after the effect (opacity alone doesn't do the job)
                    popup.css('display', 'none');
                });
            }, hideDelay);
        });
    });
}
