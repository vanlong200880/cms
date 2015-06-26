jQuery(document).ready(function($){
   $('.form-table .table>tbody>tr>td span.quick-edit a').on('click', function(){
        $(".form-table .table>tbody>tr").removeAttr('style');
        $(this).closest('tr').css('display', 'none');
        $('.edit-row').remove();
        var name = $(this).attr('data-name');
        var slug = $(this).attr('data-slug');
        var weight = $(this).attr('data-weight');
        var html = '';
        html +='<tr id="edit-1" class="edit-row">';
        html +='    <td colspan="6" class="">';
        html +='        <div class="column-qiuck-edit">';
        html +='            <div class="col-lg-12"><h2>Sửa nhanh</h2></div>';
        html +='            <div class="col-lg-12">';
        html +='                <div class="row">';
        html +='                    <div class="col-lg-6">';
        html +='                        <div class="form-group">';
        html +='                            <label for="name">Tên</label>';
        html +='                            <input type="text" class="form-control" value="'+name+'" id="name">';
        html +='                        </div>';
        html +='                        <div class="form-group">';
        html +='                            <label for="name">Slug</label>';
        html +='                            <input type="text" class="form-control" value="'+slug+'" id="slug">';
        html +='                        </div>';
        html +='                    </div>';
        html +='                </div>';
        html +='            </div>';
        html +='            <div class="col-lg-12">';
        html +='                <div class="row">';
        html +='                    <div class="col-lg-3">';
        html +='                        <div class="form-group">';
        html +='                            <label for="name">Vị trí</label>';
        html +='                            <input type="text" class="form-control" value="'+weight+'" id="weight">';
        html +='                        </div>';
        html +='                    </div>';
        html +='                    <div class="col-lg-3">';
		html +='                        <div class="form-group">';
        html +='                            <label>Danh mục cha</label>';
        html +='                            <select id="basic-1" class="selectpicker show-tick form-control" data-live-search="true">';
        html +='                                <option>cow</option>';
        html +='                                <option>bull</option>';
        html +='                                <option class="get-class" disabled>ox</option>';
        html +='                                <optgroup label="test" data-subtext="another test">';
        html +='                                    <option>ASD</option>';
        html +='                                    <option selected>Bla</option>';
        html +='                                    <option>Ble</option>';
        html +='                                </optgroup>';
        html +='                             </select>';
		html +='                        </div>';
        html +='                    </div>';
        html +='                </div>';
        html +='            </div>';
        html +='            <div class="col-lg-12">';
        html +='                <div class="row">';
        html +='                    <div class="col-lg-6">';
        html +='                        <div class="form-group">';
        html +='                            <button type="button" class="btn btn-default btn-back">Trở lại</button>';
        html +='                            <button type="button" class="btn btn-default btn-custom pull-right">Cập nhật</button>';
        html +='                        </div>';
        html +='                    </div>';
        html +='                </div>';
        html +='            </div>';
        html +='            <div class="col-lg-6"></div>';
        html +='        </div>';
        html +='    </td>';
        html +='</tr>';
        $(this).closest('tr').after(html);
        $('.selectpicker').selectpicker('refresh');
   });
   $('.form-table').on('click', '.table>tbody>tr .btn-back', function(){
		$(".form-table .table>tbody>tr").removeAttr('style');
		$('.edit-row').remove();
   });
   
//   function sortTable(f, n){
//	   var rows = $('#table-data tbody tr').get();
//	   rows.sort(function(a, b){
//		  var A = $(a).children('td').eq(n).text().toUpperCase();
//		  var B = $(b).children('td').eq(n).text().toUpperCase();
//		  if(A < B)
//			  return -1*f;
//		  if(A > B)
//			  return 1*f;
//		  return 0;
//	   });
//	   $.each(rows, function(index, row){
//		  $('#table-data').children('tbody').append(row); 
//	   });
//   }
//   var f = 1;
//   $("#table-data>thead>tr>th").click(function(){
//	   if(!$(this).hasClass('no-sort'))
//	   {
//		   f *= -1;
//			var n = $(this).prevAll().length;
//			sortTable(f,n);
//	   }
//    
//	});

	/* menu */
	$('.menu-sliderbar').on('click', function(){
		if($(this).hasClass('on'))
		{
			$('.sidebar').removeAttr('style');
			$('.main').removeAttr('style');
			$(this).removeClass('on');
		}
		else
		{
			$(this).addClass('on');
			$('.sidebar').css('margin-left','-220px');
			$('.main').css('margin-left','0');
		}
		
	}); 
	$('.menu-top ul.menu-nav>li>a').on('click', function(){
		$(this).addClass('active');
		var item = $(this).siblings('ul.item');
		$('.menu-top ul.menu-nav li ul.item').not(item).slideUp(function(){
			$(this).siblings('a').children('i').removeClass('fa-rotate-90');
		});
		item.slideToggle(function(){
			$(this).siblings('a').children('i').toggleClass('fa-rotate-90');
		});
	});
	(function($){
		$(window).load(function(){
			$(".sidebar").mCustomScrollbar();
		});
	})(jQuery);
});