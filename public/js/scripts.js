jQuery(document).ready(function($){
    /* show message */
    function isEmpty( el ){
      return !$.trim(el.html())
    }
    if (!isEmpty($('#messages'))) {
        $("#messages").addClass("show");
    }
    setTimeout(function() {
        $("#messages").removeClass("show");
        setTimeout(function(){
            $("#messages").empty();
        }, 500);
      }, 4000);
    /* end show message */
		
    // generator alias
   $("#post-name").on('keyup, keypress', function(){
       document.getElementById("post-alias").value = filterName();
   })
   function filterName()
   {
			var str = (document.getElementById("post-name").value);
			str= str.toLowerCase(); // chuyển chuỗi sang chữ thường để xử lý
			/* tìm kiếm và thay thế tất cả các nguyên âm có dấu sang không dấu*/
			str= str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g,"a");
			str= str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g,"e");
			str= str.replace(/ì|í|ị|ỉ|ĩ/g,"i");
			str= str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g,"o");
			str= str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g,"u");
			str= str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g,"y");
			str= str.replace(/đ/g,"d");
			str= str.replace(/&amp;/g, "");
			str= str.replace(/!|@|%|`|\$|\^|\*|\(|\)|\}|\{|\]|\[|[\|]|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'| |\"|\&|\#|\\|~|_/g,"-");
			/* tìm và thay thế các kí tự đặc biệt trong chuỗi sang kí tự - */
			str= str.replace(/-+-/g,"-"); //thay thế 2- thành 1-
			str= str.replace(/^\-+|\-+$/g,"");//cắt bỏ ký tự - ở đầu và cuối chuỗi
			//document.getElementById("alias").value = str; // xuất kết quả xữ lý ra input mới
			return str;
   }
    
    
    
//   $('.form-table .table>tbody>tr>td span.quick-edit a').on('click', function(){
//        $(".form-table .table>tbody>tr").removeAttr('style');
//        $(this).closest('tr').css('display', 'none');
//        $('.edit-row').remove();
//        var name = $(this).attr('data-name');
//        var slug = $(this).attr('data-slug');
//        var weight = $(this).attr('data-weight');
//        var html = '';
//        html +='<tr id="edit-1" class="edit-row">';
//        html +='    <td colspan="7" class="">';
//        html +='        <div class="column-qiuck-edit">';
//        html +='            <div class="col-lg-12"><h2>Sửa nhanh</h2></div>';
//        html +='            <div class="col-lg-12">';
//        html +='                <div class="row">';
//        html +='                    <div class="col-lg-6">';
//        html +='                        <div class="form-group">';
//        html +='                            <label for="name">Tên</label>';
//        html +='                            <input type="text" class="form-control" value="'+name+'" id="name">';
//        html +='                        </div>';
//        html +='                        <div class="form-group">';
//        html +='                            <label for="name">Slug</label>';
//        html +='                            <input type="text" class="form-control" value="'+slug+'" id="slug">';
//        html +='                        </div>';
//        html +='                    </div>';
//        html +='                </div>';
//        html +='            </div>';
//        html +='            <div class="col-lg-12">';
//        html +='                <div class="row">';
//        html +='                    <div class="col-lg-3">';
//        html +='                        <div class="form-group">';
//        html +='                            <label for="name">Vị trí</label>';
//        html +='                            <input type="text" class="form-control" value="'+weight+'" id="weight">';
//        html +='                        </div>';
//        html +='                    </div>';
//        html +='                    <div class="col-lg-3">';
//		html +='                        <div class="form-group">';
//        html +='                            <label>Danh mục cha</label>';
//        html +='                            <select id="basic-1" class="selectpicker show-tick form-control" data-live-search="true">';
//        html +='                                <option>cow</option>';
//        html +='                                <option>bull</option>';
//        html +='                                <option class="get-class" disabled>ox</option>';
//        html +='                                <optgroup label="test" data-subtext="another test">';
//        html +='                                    <option>ASD</option>';
//        html +='                                    <option selected>Bla</option>';
//        html +='                                    <option>Ble</option>';
//        html +='                                </optgroup>';
//        html +='                             </select>';
//		html +='                        </div>';
//        html +='                    </div>';
//        html +='                </div>';
//        html +='            </div>';
//        html +='            <div class="col-lg-12">';
//        html +='                <div class="row">';
//        html +='                    <div class="col-lg-6">';
//        html +='                        <div class="form-group">';
//        html +='                            <button type="button" class="btn btn-default btn-back">Trở lại</button>';
//        html +='                            <button type="button" class="btn btn-default btn-custom pull-right">Cập nhật</button>';
//        html +='                        </div>';
//        html +='                    </div>';
//        html +='                </div>';
//        html +='            </div>';
//        html +='            <div class="col-lg-6"></div>';
//        html +='        </div>';
//        html +='    </td>';
//        html +='</tr>';
//        $(this).closest('tr').after(html);
//        $('.selectpicker').selectpicker('refresh');
//   });
//   
//   $('.form-table').on('click', '.table>tbody>tr .btn-back', function(){
//		$(".form-table .table>tbody>tr").removeAttr('style');
//		$('.edit-row').remove();
//   });
   
   
   $(".language ul>li>a").on('click', function(){
	   var lang = $(this).attr('data-lang');
	   $input = $("div.lang-"+lang);
	   $(".box .box-language").addClass('hidden');
	   $input.removeClass('hidden');

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
            $(this).removeClass('on');
            if(width < 667){
                $('.sidebar').removeAttr('style');
                $('.main').removeAttr('style');
                $('.sidebar').css('margin-left','-220px');
                $('.main').css('margin-left','0');
            }
            else
            {
                $('.sidebar').removeAttr('style');
                $('.main').removeAttr('style');
            }			
		}
		else
		{
            $(this).addClass('on');
            width = window.innerWidth;
            if(width < 667)
            {
                $('.main').css('margin-right','-220px');
                $('.sidebar').css('margin-left','0');
                $('.main').css('margin-left','220px');
            }
            else
            {
                $('.sidebar').css('margin-left','-220px');
                $('.main').css('margin-left','0');
            }
		}	
	}); 
    var target = $('.menu-top ul.menu-nav>li');
    if(target.find('active'))
    {
        $('.menu-top ul.menu-nav>li.active').children('ul.item').slideToggle(function(){
            target.children('a i').toggleClass('fa-rotate-90');
        });
    }
	$('.menu-top ul.menu-nav>li>a').on('click', function(){
        $('.menu-top ul.menu-nav li').not(this).removeClass('active');
        $('.menu-top ul.menu-nav li a').not(this).removeClass('active');
		$(this).parent('li').addClass('active');
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
    
    var width_load = window.innerWidth;
    if(width_load < 667)
    {
        $('.sidebar').css('margin-left','-220px');
        $('.main').css('margin-left','0');
    }
    window.onresize = function() {
        var width = window.innerWidth;
        if(width < 667)
        {
            $('.sidebar').css('margin-left','-220px');
			$('.main').css('margin-left','0');
        }
        else
        {
            $('.sidebar').removeAttr('style');
			$('.main').removeAttr('style');
        }
    }
    
    // check all item
    $(".form-all").change(function(){
        $("input.check-all:checkbox").prop('checked', $(this).prop("checked"));
    });
    // tooltip
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
    
    // check active background color
    $("#all input:checkbox").on('click', function(){
        if ($(this).is(':checked')) {
            $(".form-table table tbody tr").addClass('active');
        }else{
            $(".form-table table tbody tr").removeClass('active');
        }
    });
    $("input.check-all:checkbox").on('click', function(){
        if ($(this).is(':checked')) {
            $(this).closest('tr').addClass('active');
        }else{
            $(this).closest('tr').removeClass('active');
        }
    });
});