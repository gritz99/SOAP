    var lenSliderJSReady = function(ajaxServerURL, tipsy_check, isPluginPage, confirmText, confirmThumbText, noSkinsText, sliderComment, errTitle, errGeneral, maximizeStr, minimizeStr, skinSettingsConfirmStr, allowedUrlsArr, sliderErrStr, bannerErrStr) {
        if(isPluginPage) {
            if(jQuery("#ls_save_metabox").length) {
                var needleTop = (jQuery("#ls_save_metabox").offset().top) - parseInt(jQuery("#wpadminbar").css('height')) - 10;
                jQuery(window).scroll(function() {
                    var scrollTop = jQuery(window).scrollTop();
                    if(scrollTop >= needleTop) {
                        jQuery("#ls_save_metabox").addClass('ls_fixed');
                    } else {
                        jQuery("#ls_save_metabox").removeClass('ls_fixed');
                    }
                });
            }
            if(jQuery(".ls-sortable").length) {
                jQuery(".ls-sortable").each(function() {
                    var $slidernum = jQuery(this).attr('id').replace(/slidernum_/, ''),
                    $plhldr =(jQuery.cookie("folding_"+$slidernum) == "svernuto") ? "ushmin" : "ls-state-highlight";
                    jQuery(this).sortable({
                        axis: 'y',
                        placeholder: $plhldr,
                        handle: 'h3'
                    });
                });
            }
            if(jQuery(".sliders_nav").length) jQuery(".sliders_nav").onePageNav();
            if(tipsy_check) {
                if(jQuery(".atipsy").length)   jQuery(".atipsy").tipsy({gravity: 'e'});
                if(jQuery(".atipsy_s").length) jQuery(".atipsy_s").tipsy({gravity: 's'});
                if(jQuery(".atipsy_w").length) jQuery(".atipsy_w").tipsy({gravity: 'w'});
            }
        }

        var minLength = 3;
        if(jQuery(".ls_slide_image_inner_controls").length) jQuery(".ls_slide_image_inner_controls ul li a").css({'opacity':.6});
        if(jQuery(".ls_abs_size").length) jQuery(".ls_abs_size").css({'opacity':.5});
        if(jQuery(".ls_banner_close").length) jQuery(".ls_banner_close, .ps_del").css({'opacity':.7});
        if(jQuery(".ls_slide_image_inner_overlay").length) jQuery(".ls_slide_image_inner_overlay").hide();
        //jQuery("#post").attr("enctype", "multipart/form-data");
        jQuery(".ls_bbutton").mouseup(function(){jQuery(this).removeClass('push')}).mousedown(function(){jQuery(this).addClass('push');});

        jQuery("input.prevent").focus(function() {
            if(jQuery(this).val() == sliderComment) {
                jQuery(this).val('');
                jQuery(this).removeClass('prevent');
            }
        }).blur(function() {
            if(jQuery(this).val() == '') {
                jQuery(this).val(sliderComment);
                if(!jQuery(this).hasClass('prevent')) jQuery(this).addClass('prevent');
            }
        });

        //Sliders tabs
        jQuery("ul.tit_tabs li").live('click', function() {
            var $link  = jQuery(this).find("a"),
            $slidernum = $link.attr('class').replace(/sl_tabs_/, '');
            jQuery(".sl_content_"+$slidernum).hide();
            jQuery($link.attr("href")).show();
            jQuery("ul.tit_tabs_"+$slidernum+" li").removeClass("active");
            jQuery(this).addClass("active");
            return false;
        });

        jQuery(".chbx_is_thumb").live('click', function() {
            var $id = jQuery(this).attr('id').split("_")[3];
            if(jQuery(this).prop("checked")) {
                if(jQuery("#ls_thumb_max_width_"+$id).attr("disabled") == "disabled") {
                    jQuery("#ls_thumb_max_width_"+$id).removeAttr("disabled");
                    jQuery(".tgl_thumb_"+$id).show();
                }
            } else {
                if(jQuery("#ls_thumb_max_width_"+$id).attr("disabled") != "disabled") {
                    jQuery("#ls_thumb_max_width_"+$id).attr("disabled", "disabled");
                    jQuery(".tgl_thumb_"+$id).hide();
                }
            }
        });

        /*jQuery(".pls_add").live('click', function() {
            jQuery(".pls_add_load").show();
            var $slidernum = jQuery('select#post_slider option:selected').val();
            var $ls_post_id = jQuery("#ls_post_id").val();
            jQuery.post(ajaxServerURL,
                {slidernum:$slidernum, ls_post_id:$ls_post_id, act:'append_post_slider'},
                function(data) {
                    jQuery("#post_slider_append").prepend(jQuery(data.ret).fadeIn('slow'));
                    //jQuery("select#post_slider option:contains('"+$slidernum+"')").attr('disabled','disabled');
                    jQuery(".pls_add_load").hide();
                }, "json"
            );
        });*/

        jQuery(".ls_slide_image_inner_controls ul li a").hover(
            function() {jQuery(this).css({'opacity': 1});},
            function() {jQuery(this).css({'opacity': .7});}
        );

        jQuery(".ls_banner_close, .ps_del").hover(
            function() {jQuery(this).css({'opacity':1});},
            function() {jQuery(this).css({'opacity':.6});}
        );

        jQuery(".ls_slide_image_inner").hover(
            function() {jQuery(this).find(".ls_abs_size").fadeIn("fast");},
            function() {jQuery(this).find(".ls_abs_size").hide();}
        );

        jQuery(".handlediv").live('click', function() {
            var $thisElem = jQuery(this).parents("li");
            if($thisElem.hasClass("min")) $thisElem.removeClass("min");
            else $thisElem.addClass("min");
        });

        //simple remove post banner form
        jQuery(".ls_ps_new").live('click', function() {
            var $pre_info = jQuery(this).attr('id').replace(/a_ls_ps_/, '').split('_');
            jQuery("#ls_ps_"+$pre_info[0]).fadeOut(500, function() {jQuery(this).remove();});
        });

        jQuery("input.blink").live('change', function() {
            var $to_list = jQuery.trim(jQuery(this).val()),
            $pre_info    = jQuery(this).attr('id').replace(/blink_/, '').split('_'),
            $name        = $pre_info[0],
            $slidernum   = $pre_info[1],
            $n           = $pre_info[2],
            $banner_k    = $pre_info[3];
            jQuery("#blink_append_"+$slidernum+"_"+$n).html('');
            if($to_list != 'blink_lsurl') {
                jQuery("#blink_append_"+$slidernum+"_"+$n).addClass('bload2');
                jQuery("#ls_link_"+$slidernum+"_"+$n).attr("disabled", "disabled");
            } else jQuery("#ls_link_"+$slidernum+"_"+$n).removeAttr("disabled");
            jQuery.post(ajaxServerURL,
                {slidernum:$slidernum,name:$name,n:$n,banner_k:$banner_k,to_list:$to_list,act:'links_variants'},
                function(data) {
                    if($to_list != 'blink_lsurl') {
                        jQuery("#blink_append_"+$slidernum+"_"+$n).removeClass('bload2');
                        if(data.ret != '') jQuery("#blink_append_"+$slidernum+"_"+$n).html(data.ret);
                        else jQuery("#blink_append_"+$slidernum+"_"+$n).html('');
                        jQuery("#post_hidden_uth_ls_link_"+$slidernum+"_"+$n).html(data.uth);
                    } else {
                        jQuery("#post_hidden_uth_ls_link_"+$slidernum+"_"+$n).html(data.uth);
                        jQuery("#post_hidden_ls_link_"+$slidernum+"_"+$n).html('');
                        jQuery("#ls_link_"+$slidernum+"_"+$n).removeAttr("disabled").attr('name', 'binfo['+$slidernum+'][ls_link][]');
                        jQuery("#url_type_id_"+$slidernum+"_"+$n).find('input').val('');
                    }
                }, "json"
            );
        });

        jQuery(".ls_post_url select").live('change', function() {
            var $pre_info = jQuery(this).attr('id').replace(/blink_select_/, '').split('_'),
            $slidernum    = $pre_info[0],
            $banner_k     = $pre_info[1],
            $n            = $pre_info[2],
            $id           = parseInt(jQuery(this).val()),
            $url_type     = jQuery("#post_hidden_uth_ls_link_"+$slidernum+"_"+$n+" input").val();
            jQuery.post(ajaxServerURL,
                {slidernum:$slidernum,n:$n,id:$id,banner_k:$banner_k,url_type:$url_type,act:'link_variants_url'},
                function(data) {
                    jQuery("#ls_link_"+$slidernum+"_"+$n).removeAttr("name").val(data.url);
                    jQuery("#url_type_id_"+$slidernum+"_"+$n).html(data.uti);
                    jQuery("#post_hidden_ls_link_"+$slidernum+"_"+$n).html(data.ret);
                }, "json"
            );
        });

        //delete post banner
        jQuery(".ls_ps_todel").live('click', function() {
            if(confirm(confirmText)) {
                var $pre_info = jQuery(this).attr('id').replace(/a_ls_ps_/, '').split('_'),
                $post_id      = $pre_info[1],
                $slidernum    = $pre_info[0],
                $att_id       = $pre_info[2],
                $att_thumb_id = $pre_info[3];
                jQuery("#del_"+$slidernum).hide();
                jQuery("#del2_"+$slidernum).show();
                jQuery.post(ajaxServerURL,
                    {slidernum:$slidernum, post_id:$post_id, att_id:$att_id, att_thumb_id:$att_thumb_id, act:'delete_post_slider'},
                    function(data) {
                        if(data.del_ret) jQuery("#ls_ps_"+$slidernum).fadeOut(500, function() {jQuery(this).remove();});
                        else {
                            jQuery("#del_"+$slidernum).show();
                            jQuery("#del2_"+$slidernum).hide();
                        }
                    }, "json"
                );
            }
        });

        jQuery(".swskin").live('change', function() {
            var $slidernum = jQuery(this).attr('class').split(' ')[1].replace(/swskin_/, ''),
            $skin_name     = jQuery(this).val();//skin name
            jQuery(".swskin_"+$slidernum).val($skin_name);
            if(confirm(skinSettingsConfirmStr)) {
                jQuery.cookie('skin_set_'+$slidernum, $skin_name);
            }
        });

        //Set global settings for slider custom settings
        jQuery(".set_global_set_sldr").live('click', function() {
            var $slidernum = jQuery(this).attr('id').replace(/set_glob_/, ''),
            $this_li       = jQuery(this).parent("div");
            $this_li.addClass("bload");
            jQuery.post(ajaxServerURL,
                {act:'get_settings_global'},
                function(data) {
                    jQuery(".ls_images_maxsize_" +$slidernum).val(data.ret.ls_images_maxsize);
                    jQuery(".ls_images_maxwidth_"+$slidernum).val(data.ret.ls_images_maxwidth);
                    jQuery(".ls_banners_limit_"  +$slidernum).val(data.ret.ls_banners_limit);
                    jQuery(".ls_slider_images_quality_" +$slidernum).val(data.ret.ls_slider_images_quality);
                    $this_li.removeClass("bload");
                    jQuery("#set_local_"+$slidernum).show();
                }, "json"
            );
        });

        //Return local slider settings
        jQuery(".set_local_set_sldr").live('click', function() {
            var $slidernum = jQuery(this).attr('id').replace(/set_local_/, ''),
            $this_li       = jQuery(this).parent("div");
            $this_li.addClass("bload");
            jQuery.post(ajaxServerURL,
                {slidernum:$slidernum, act:'get_settings_local'},
                function(data) {
                    jQuery(".ls_images_maxsize_" +$slidernum).val(data.ret.ls_images_maxsize);
                    jQuery(".ls_images_maxwidth_"+$slidernum).val(data.ret.ls_images_maxwidth);
                    jQuery(".ls_banners_limit_"  +$slidernum).val(data.ret.ls_banners_limit);
                    jQuery(".ls_slider_images_quality_" +$slidernum).val(data.ret.ls_slider_images_quality);
                    $this_li.removeClass("bload");
                    jQuery("#set_local_"+$slidernum).hide();
                }, "json"
            );
        });

        //Set skin settings for slider
        jQuery(".set_skin_set_sldr").live('click', function() {
            var $slidernum = jQuery(this).attr('id').replace(/set_skin_/, ''),
            $this_li       = jQuery(this).parent("div");
            $this_li.addClass("bload");
            jQuery.post(ajaxServerURL,
                {slidernum:$slidernum, act:'get_settings_skin'},
                function(data) {
                    jQuery(".ls_images_maxsize_" +$slidernum).val(data.ret.ls_images_maxsize);
                    jQuery(".ls_images_maxwidth_"+$slidernum).val(data.ret.ls_images_maxwidth);
                    jQuery(".ls_banners_limit_"  +$slidernum).val(data.ret.ls_banners_limit);
                    jQuery(".ls_slider_images_quality_" +$slidernum).val(data.ret.ls_slider_images_quality);
                    $this_li.removeClass("bload");
                }, "json"
            );
        });

        //Delete image
        jQuery(".ls_slide_image_inner_controls ul li a.c_del").live('click', function() {
            var $delThumb = (confirm(confirmThumbText))?true:false,
            $thisarr      = jQuery(this).attr('id').replace(/mbgdel_/, '').split("_"),
            $this_id      = $thisarr[0],
            $thumb_id     = $thisarr[1],
            $slidernum    = $thisarr[2];
            //$post_id      = $thisarr[3];
            jQuery(".c_del, .c_thdel").hide();
            if($delThumb) {
                jQuery("#overlay_"+$this_id).show();
                if($thumb_id) jQuery("#overlay_"+$thumb_id).show();
                setTimeout(function () {
                    jQuery.post(ajaxServerURL,
                        {attachment_id:$this_id, thumb_del:$delThumb, thumb_id:$thumb_id, slidernum:$slidernum/*, post_id:$post_id*/, act:'del_image'},
                        function(data) {
                            if(data.del_ret == true) {
                                jQuery(".c_del, .c_thdel").show();
                                jQuery("#overlay_"+$this_id).hide();
                                jQuery("#delatt_"+$this_id).val('');
                                jQuery("#slide_image_"+$this_id).remove();
                                if($delThumb) {
                                    jQuery("#overlay_"+$thumb_id).hide();
                                    jQuery("#delthatt_"+$thumb_id).val('');
                                    jQuery("#slide_image_thumb_"+$thumb_id).remove();
                                }
                            }
                        }, "json"
                    );
                }, 500);
            }
            return false;
        });

        //Delete thumb
        jQuery(".ls_slide_image_inner_controls ul li a.c_thdel").live('click', function() {
            if(confirm(confirmText)) {
                var $thisarr = jQuery(this).attr('id').replace(/mbgthdel_/, '').split("_"),
                $this_id     = $thisarr[0],
                $thumb_id    = $thisarr[1],
                $slidernum   = $thisarr[2];
                //$post_id     = $thisarr[3];
                jQuery(".c_del, .c_thdel").hide();
                jQuery("#overlay_"+$thumb_id).show();
                setTimeout(function () {
                    jQuery.post(ajaxServerURL,
                        {attachment_id:$this_id, thumb_id:$thumb_id, slidernum:$slidernum/*, post_id:$post_id*/, act:'del_thumb'},
                        function(data) {
                            if(data.del_ret == true) {
                                jQuery(".c_del, .c_thdel").show();
                                jQuery("#overlay_"+$thumb_id).hide();
                                jQuery("#delthatt_"+$thumb_id).val('');
                                jQuery("#slide_image_thumb_"+$thumb_id).remove();
                            }
                        }, "json"
                    );
                }, 500);
                return false;
            }
        });

        //Delete banner
        jQuery(".liveajaxbdel").live('click', function() {
            if(confirm(confirmText)) {
                var $slidernum = jQuery(this).parents("ul.ls-sortable:first").attr('id').replace(/slidernum_/, ''),
                $this_arr      = jQuery(this).attr('id').replace(/liveajaxbdel_/, '').split("_"),
                $this_id       = $this_arr[0],
                $thumb_id      = $this_arr[1],
                $post_id       = $this_arr[2];
                jQuery(".c_del").hide();
                jQuery("#boverlay_"+$this_id).show();
                setTimeout(function () {
                    jQuery.post(ajaxServerURL,
                        {banner_id:$this_id, thumb_id:$thumb_id, slidernum:$slidernum, post_id:$post_id, act:'del_banner'},
                        function(data) {
                            if(data.del_ret == true) {
                                jQuery("#bitem_"+$this_id).remove();
                                jQuery(".c_del").show();
                                if(jQuery("#slidernum_"+$slidernum+" li").length == 0) {
                                    if($slidernum == 0) addBannerAjax($slidernum, 0);
                                    else jQuery("#slidernum_"+$slidernum).parents(".ls_metabox").remove();
                                }
                            }
                        }, "json"
                    );
                }, 500);
            }
            return false;
        });

        //Ajax added banner form delete
        jQuery(".livebdel").live('click', function() {
            jQuery(this).parents("li").fadeOut(500, function() {jQuery(this).remove();});
            return false;
        });

        //Ajax added slider form delete
        jQuery(".slremove").live('click', function() {
            var $sliders_length = (jQuery(".ls_metabox").length)-1;
            jQuery(this).parents(".ls_metabox").remove();
            if($sliders_length <= 0) addSliderAjax(0);
            return false;
        });

        var addSliderAjax = function(count_sliders, removeEl, skin_name) {
            jQuery.post(ajaxServerURL,
                {count_sliders:count_sliders, skin_name:skin_name, act:'add_slider'},
                function(data) {
                    if(count_sliders <= data.sliders_limit && data.slider_item != false) {
                        if(removeEl) removeEl.removeClass("bload");
                        jQuery('#lensliders').append(jQuery(data.slider_item).fadeIn('slow'));
                        scrollToAnchor('slider_metabox_'+count_sliders);
                    } else if(removeEl) removeEl.removeClass("bload");
                }, "json"
            );
        };

        //Delete slider
        jQuery(".slajaxdel").live('click', function() {
            if(confirm(confirmText)) {
                var $sliders_length = jQuery(".ls_metabox").length,
                $slidernum = jQuery(this).attr("id").replace(/delslider_/, '');
                jQuery.post(ajaxServerURL,
                    {slidernum:$slidernum, act:'del_slider'},
                    function(data) {
                        if(data.del_ret == true) {
                            jQuery(".slnum_"+$slidernum).fadeTo(2000, 0, function() {
                                jQuery(this).remove();
                            });
                            jQuery("#sliders_nav_li_"+$slidernum).fadeTo(2000, 0, function() {
                                jQuery(this).remove();
                            });
                            jDelCookie('folding_'+$slidernum);
                            $sliders_length--;
                            if($sliders_length <= 0) addSliderAjax(0);
                        }
                    }, "json"
                );
            }
        });

        //Add new slider form
        jQuery(".add_slider").live('click', function() {
            var $this_parent = jQuery(this).parent("div"),
            $skin_name       = jQuery('select[name=slider_ajax_skins] option:selected').val(),
            $count_sliders   = jQuery(".ls_metabox").length;
            $this_parent.addClass("bload");
            setTimeout(function () {
                addSliderAjax($count_sliders, $this_parent, $skin_name);
                $count_sliders++;
            }, 200);
        });

        //Add new banner form
        jQuery(".add_banner").live('click', function() {
            var $this_parent = jQuery(this).parent("div"),
            $slidernum       = jQuery(this).attr('id').replace(/banner_slider_/, ''),
            $skin_name       = jQuery('input:hidden[name=slider_skin_name_'+$slidernum+']').val(),
            $count_banners   = jQuery("#slidernum_"+$slidernum+" li.bitem").length;
            $this_parent.addClass("bload");
            jQuery(".ls_box_content_"+$slidernum).hide();
            jQuery("#banners_"+$slidernum).show();
            jQuery("ul.tit_tabs_"+$slidernum+" li").removeClass("active");
            jQuery(".first_li_"+$slidernum).addClass("active");
            setTimeout(function () {
                addBannerAjax($slidernum, $count_banners, $this_parent, $skin_name);
                $count_banners++;
            }, 200);
        });

        var addBannerAjax = function(slidernum, count_banners, removeEl, skin_name) {
            jQuery.post(ajaxServerURL,
                {count_banners:count_banners, slidernum:slidernum, skin_name:skin_name, act:'add_banner'},
                function(data) {
                    if(count_banners <= data.banners_limit && data.banner_item != false) {
                        if(removeEl) removeEl.removeClass("bload");
                        jQuery('#slidernum_'+slidernum).append(jQuery(data.banner_item).fadeIn('slow'));
                        scrollToAnchor('anchor_'+slidernum+count_banners);
                    } else if(removeEl) removeEl.removeClass("bload");
                }, "json"
            );
            return false;
        };

        //Checking values while form submiting
        jQuery("#ls_form").submit(function() {
            //var $errors = new Object();
            //var $i=0;
            jQuery(".tcheck").each(function() {
                //var $slidernum = jQuery(this).attr('id').split("_")[2];
                //var $bannernum = parseInt(jQuery(this).attr('id').split("_")[3])+1;
                jQuery(this).removeClass('txt_error');
                var this_val = jQuery(this).val().toString();
                //$errors[$slidernum] = new Object();
                //$errors[$slidernum][$bannernum] = new Object();
                //$errors[$slidernum][$bannernum][$i] = new Array();
                //var $n=0;
                if(this_val == '') {
                    jQuery(this).addClass('txt_error');
                    //$errors[$slidernum][$bannernum][$i][$n] = "Field #"+$name+" is empty<br />";
                    //$n++;
                }
                if(this_val.length < minLength) {
                    jQuery(this).addClass('txt_error');
                    //$errors[$slidernum][$bannernum][$i][$n] = "Value of field #"+$name+" less than "+minLength+"<br />";
                    //$n++;
                }
                var $name = jQuery(this).attr("id").split('_')[1];
                if($name == 'link') {
                    if(!isValidUrl(this_val)) jQuery(this).addClass('txt_error');
                    else jQuery(this).removeClass('txt_error');
                    //$errors[$slidernum][$bannernum][$i][$n] = "Field #"+$name+" did not pass validation<br />";
                    //$n++;
                }
                //$i++;
            });
            if(jQuery(".ls_maxinput").hasClass('txt_error')) {
                /*var $ret = '';
                if(jQuery.isPlainObject($errors)) {
                    //sliders
                    jQuery.each($errors, function(sl_index, b_arr) {
                        $ret += "<br /><strong>"+lsReplace(sliderErrStr, sl_index)+"</strong><br />";
                        if(jQuery.isPlainObject(b_arr)) {
                            //banners
                            jQuery.each(b_arr, function(b_index, banner_fields) {
                                $ret += "<br />"+lsReplace(bannerErrStr, parseInt(b_index))+"<br />";
                                if(jQuery.isPlainObject(banner_fields)) {
                                    //fields
                                    jQuery.each(banner_fields, function(field_index, error_arr) {
                                        if(jQuery.isArray(error_arr)) {
                                            $ret += "Field #"+field_index+" errors<br />";
                                            //err_num
                                            jQuery.each(error_arr, function(index, err) {
                                                $ret += "&mdash;&nbsp;<em>"+err+"</em>";
                                            });
                                        }
                                    });
                                }
                            });
                        }
                    });
                }*/
                //if($ret != '') {
                    jAlert(errGeneral, errTitle);
                    return false;
                //}
            }
            else jQuery(this).submit();
        });

        jQuery("#ls_slins_submit").submit(function() {
            if(jQuery(this).find("input[type=file]").val()!='') jQuery(this).submit();
            else return false;
        });

        //Slide all blocks
        jQuery(".utm_ul a.sl_banners").live('click', function() {
            var $slidernum = jQuery(this).attr("id").replace(/sl_banner_/, ''),
            $thisElem      = jQuery(this);
            if($thisElem.find("span").hasClass("minus")) {
                //Razvernuto - svora4ivaem
                jQuery.cookie('folding_'+$slidernum, 'svernuto', {expires: 2});
                jQuery("#slidernum_"+$slidernum+" li").addClass("min");
                jQuery("#slidernum_"+$slidernum).sortable("option", "placeholder", 'ushmin');
                $thisElem.find("span").removeClass("minus").addClass("plus").html(maximizeStr);

                jQuery("#ls_slider_set_"+$slidernum).hide();
                jDelCookie('foldset_'+$slidernum);
                hideLSSliderButtlive($slidernum);
            } else {
                //Svernuto - razvora4ivaem
                jDelCookie('folding_'+$slidernum);
                jQuery("#slidernum_"+$slidernum+" li").removeClass("min");
                jQuery("#slidernum_"+$slidernum).sortable("option", "placeholder", 'ls-state-highlight');
                $thisElem.find("span").removeClass("plus").addClass("minus").html(minimizeStr);
            }
        });

        //Delete skin
        jQuery(".skin_allow_delete").live('click', function() {
            if(confirm(confirmText)) {
                var t         = jQuery(this),
                $skin_name    = t.attr('id').replace(/skin_/, ''),
                $skins_length = jQuery("ul.fullwidth li.skinli").length;
                jQuery.post(ajaxServerURL,
                        {skin_name:$skin_name, act:'del_skin'},
                        function(data) {
                            if(data.del_ret == true) {
                                t.parents("li.skinli").fadeOut('slow', function() {
                                    jQuery(this).remove();
                                    $skins_length--;
                                    if($skins_length == 0) jQuery(".ls_box_content").html(noSkinsText);
                                });
                            }
                        }, "json"
                );
            }
        });

        var isValidUrl = function(data) {
            var pattern = new RegExp(/^((http|https):\/\/)?([a-z0-9\-]+\.)?([a-z0-9\-]+\.)?[a-z0-9\-]+\.[a-z0-9]{2,4}(\.[a-z0-9]{2,4})?(\/.*)?$/i);
            if(pattern.test(data) || (jQuery.inArray(data, allowedUrlsArr)) > -1) return true;
            return false;
        };

        var jDelCookie = function(cookieName) {
            jQuery.cookie(cookieName, null);
            return false;
        };

        var scrollToAnchor = function(id) {
            var $this_el  = jQuery("#"+id),
            $final_offset = $this_el.offset().top - ((jQuery(window).height() - $this_el.height())/2);
            jQuery('html,body').animate({scrollTop: $final_offset},'slow');
            return false;
        };

        var lsReplace = function(str, r) {
            return str.replace("{%torep%}", r);
        }
    };