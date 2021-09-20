function myTable(a, t) {    
    var e = {
        displayLength: 10,
        scrollX: false,
        paging: !0,
        dom: "<'row'<'col-xs-5'l><'col-xs-7'f>r<'clear'>>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        language: {
            loadingRecords: "Tunggu sejenak - memuat...",
            search: "<span>Pencarian:</span> ",
            lengthMenu: "_MENU_ data per halaman",
            info: "Menampilkan <span>_START_</span> s/d <span>_END_</span> dari <span>_TOTAL_</span> data",
            emptyTable: "Tidak ada data",
            infoEmpty: "Tidak ada data",
            zeroRecords: "Tidak ada data",
            paginate: {
                next: '<i class="zmdi zmdi-chevron-right"></i>',
                previous: '<i class="zmdi zmdi-chevron-left"></i>'
            }
        },
        autoWidth: true,
        processing: !0,
        serverSide: !0,
        deferRender: !0,
        responsive: false,
        fnServerData: function (a, t, e, i) {
            i.jqXHR = $.ajax({
                dataType: "json",
                type: "POST",
                url: a,
                data: t,
                success: e
            })
        },
        fnDrawCallback: function (a) {
            scrollTo(), $("select").selectpicker()
        }
    },
    i = $.extend(e, t);
    null != myApp.oTable && myApp.oTable.fnDestroy(), myApp.oTable = $(a).dataTable(i), $(".dataTables_filter").addClass("pull-right m-r-10"), $(".dataTables_filter input").attr("placeholder", "kata pencarian.."), $(".dataTables_filter input").addClass("search-field"), $(".dataTables_info").addClass("p-l-20 p-t-25 p-b-25"), $(".dataTables_length").addClass("p-l-20"), $(".dataTables_paginate").addClass("m-r-20"), $(".dataTables_processing").css({
        position: "relative",
        width: "200px",
        height: "auto",
        right: "85px",
        top: "20px",
        "text-align": "right"
    }), $(a).on("click", ".btn", function (a) {
        var t = $(this).attr("data-bind"),
                e = $(this).attr("data-tabel"),
                i = "#table" + $("#tabel").val(),
                o = "#form" + $("#tabel").val();
        switch (t) {
            case "ubah":
                $.ajax({url: e + "/form/" + $("#tabel").val() + "/" + $(this).attr("data-kode") + "/" + $(this).attr("data-bhs"),
                    type: "POST",
                    dataType: "html",
                    beforeSend: function () {
                        $(".page-loader").fadeIn()
                    }, success: function (data) {
                        $(o).html(data);
                        $(i).hide();
                        $('#containerform').show();
                        $(".page-loader").fadeOut();
                        scrollTo();
                    }})
                break;
            case "salin":
                $(".page-loader").fadeIn()
                $(".row #containerform").isLoading({
                    text: "Memuat..",
                    position: "overlay",
                    tpl: '<span class="isloading-wrapper %wrapper%">%text%<div class="preloader pls-amber" style="position: absolute; top: 0px; left: -40px;"><svg class="pl-circular" viewBox="25 25 50 50"><circle class="plc-path" cx="50" cy="50" r="20"></circle></svg></div>'
                }), $.when($(o).load($(this).attr("data-get") + "/" + $("#tabel").val() + "/" + $(this).attr("data-kode") + "/salin/" + $(this).attr("data-bhs"))).done(function (a) {
                    $(i).hide(), setTimeout(function () {
                        $(".row #containerform").isLoading("hide"), $("#containerform").show(), scrollTo(); $(".page-loader").fadeOut();
                    }, 300)
                });
                break;
            case "hapus":
                $("#getto").val($(this).attr("data-get")), $("#aepYes").text("Hapus"), $("#cid").val($(this).attr("data-value")), $("#cod").val($(this).attr("data-bhs")), $(".lblModal").text("hapus " + $(this).attr("data-default")), $("#myConfirm").modal()
        }
        $("table.dataTable").resize();
    })
}
function myTable2(a, t) {
    var e = {
        fixedHeader: true,
        scrollY: '200px',
        scrollCollapse: true,
        paging: false,
        dom: "<'row'<'col-xs-5'l><'col-xs-7'f>r<'clear'>>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        language: {
            loadingRecords: "Tunggu sejenak - memuat...",
            search: "<span>Pencarian:</span> ",
            lengthMenu: "_MENU_ data per halaman",
            info: "Menampilkan <span>_START_</span> s/d <span>_END_</span> dari <span>_TOTAL_</span> data",
            emptyTable: "Tidak ada data",
            infoEmpty: "Tidak ada data",
            zeroRecords: "Tidak ada data",
            paginate: {
                next: '<i class="zmdi zmdi-chevron-right"></i>',
                previous: '<i class="zmdi zmdi-chevron-left"></i>'
            }
        },
        processing: !0,
        serverSide: !0,
        deferRender: !0,
        responsive: !1,
        destroy: true,
        fnServerData: function (a, t, e, i) {
            i.jqXHR = $.ajax({
                dataType: "json",
                type: "POST",
                url: a,
                data: t,
                success: e
            })
        },
        fnDrawCallback: function (a) {
            scrollTo(), $("select").selectpicker()
        }
    },
    i = $.extend(e, t);
    null != myApp2.oTable && myApp2.oTable.fnDestroy(), myApp2.oTable = $(a).dataTable(i), $(".dataTables_filter").addClass("pull-right m-r-10"), $(".dataTables_filter input").attr("placeholder", "kata pencarian.."), $(".dataTables_filter input").addClass("search-field"), $(".dataTables_info").addClass("p-l-20 p-t-25 p-b-25"), $(".dataTables_paginate").addClass("m-r-20"), $(".dataTables_processing").css({
        position: "relative",
        width: "200px",
        height: "auto",
        right: "85px",
        top: "20px",
        "text-align": "right"
    }), $(a).on("click", ".btn", function (a) {
        var t = $(this).attr("data-bind"),
                e = $(this).attr("data-tabel"),
                i = "#table" + $("#tabel").val(),
                o = "#form" + $("#tabel").val();
        switch (t) {
            case "ubah":
                $('#catid').val($(this).attr("data-kode"));
                $('#name').val($(this).attr("data-nama"));                
                $('#type_id').attr("data-default", $(this).attr("data-tipe"));
                $('select[name="type_id"]').val($(this).attr("data-tipe"));
                $('.selectpicker').selectpicker('refresh');
                break;
            case "hapus":
                $("#getto").val($(this).attr("data-get")), $("#aepYes").text("Hapus"), $("#cid").val($(this).attr("data-value")), $("#cod").val($(this).attr("data-bhs")), $(".lblModal").text("hapus " + $(this).attr("data-default")), $("#myConfirm").modal()
        }
        $("table.dataTable").resize();
    })
}
function scrollTo() {
    jQuery("html,body").animate({
        scrollTop: 10
    }, "slow")
}
function scrollKa(a) {
    $("html,body").animate({
        scrollTop: a.offset().top
    }, 300)
}
function fgline() {
    $("body").on("focus", ".fg-line .form-control", function () {
        $(this).closest(".fg-line").addClass("fg-toggled")
    }), $("body").on("blur", ".form-control", function () {
        var a = $(this).closest(".form-group, .input-group"),
                t = a.find(".form-control").val();
        a.hasClass("fg-float") ? 0 == t.length && $(this).closest(".fg-line").removeClass("fg-toggled") : $(this).closest(".fg-line").removeClass("fg-toggled")
    })
}
function updatePage(a, t) {
    window.setTimeout(function () {
        $(a).html(t)
    }, 300)
}
function notify(a, t) {
    $.growl({
        message: a
    }, {
        type: t,
        allow_dismiss: !1,
        label: "Cancel",
        className: "btn-xs btn-inverse",
        placement: {
            from: "top",
            align: "right"
        },
        delay: 2500,
        animate: {
            enter: "animated bounceIn",
            exit: "animated bounceOut"
        },
        offset: {
            x: 20,
            y: 85
        }
    })
}
function removeKoma(a) {
    try {
        return a.replace(/\,/g, "")
    } catch (t) {
        return a
    }
}
function addKoma(a) {
    a = removeKoma(a);
    var t = 0,
            e = new String(a);
    if (1 == (e = e.split(".").reverse()).length) {
        i = (i = new String(a)).split("").reverse();
        t = "00"
    } else {
        var i;
        i = (i = new String(e[1])).split("").reverse();
        t = e[0]
    }
    for (var o = "", l = 0; l <= i.length - 1; l++)
        o = "-" == (o = i[l] + o) ? o : (l + 1) % 3 == 0 && i.length - 1 !== l ? "," + o : o;
    return o = o + "." + t
}
function addTitik(a) {
    var t = new String(a);
    t = t.split("").reverse();
    for (var e = "", i = 0; i <= t.length - 1; i++)
        e = t[i] + e, i + 1 < 4 ? e = e : (i + 1) % 3 == 0 && t.length - 1 !== i && (e = "." + e);
    return e
}
function piceunSpasi(a) {
    try {
        return a.replace(/\ /g, "")
    } catch (t) {
        return a
    }
}
function getList(a, b, c, d, e, f, h, uri) {
    $.ajax({
        type: "POST",
        url: uri,
        data: {
            tabel: a,
            param: c,
            fld: d,
            kolom: f
        }
    }).done(function (data) {
        json = eval(data), $("#" + b).html(""), $("#" + b).append('<option value="">' + h + "</option>"), $(json).each(function () {
            var a = this.id == e ? 'selected="selected"' : "";
            $("#" + b).append('<option value="' + this.id + '" ' + a + ">" + this.id + " " + this.value + "</option>");
        }), $("#" + b).selectpicker("refresh");
    });
}
function getListTahun(a, b, c, d, e, f, h, uri) {
    $.ajax({
        type: "POST",
        url: uri,
        data: {
            tabel: a,
            param: c,
            fld: d,
            kolom: f
        }
    }).done(function (data) {
        json = eval(data), $("#" + b).html(""), $("#" + b).append('<option value="">' + h + "</option>"), $(json).each(function () {
            var a = this.id == e ? 'selected="selected"' : "";
            $("#" + b).append('<option value="' + this.id + '" ' + a + ">" + this.value + "</option>")
        }), $("#" + b).selectpicker("refresh")
    })
}

/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) && $("html").addClass("ismobile"), $(window).load(function () {
    $("html").hasClass("ismobile") || $(".page-loader")[0] && setTimeout(function () {
        $(".page-loader").fadeOut()
    }, 500)
}), $(document).ready(function () {
    var a, t, e;
    if (a = localStorage.getItem("ma-layout-status"), $("#header-2")[0] || 1 == a && ($("body").addClass("sw-toggled"), $("#tw-switch").prop("checked", !0)), $("body").on("change", "#toggle-width input:checkbox", function () {
        $(this).is(":checked") ? setTimeout(function () {
            $("body").addClass("toggled sw-toggled"), localStorage.setItem("ma-layout-status", 1)
        }, 250) : setTimeout(function () {
            $("body").removeClass("toggled sw-toggled"), localStorage.setItem("ma-layout-status", 0)
        }, 250)
    }), $("html").hasClass("ismobile") || $(".c-overflow")[0] && (t = "minimal-dark", e = "y", $(".c-overflow").mCustomScrollbar({
        theme: t,
        scrollInertia: 100,
        axis: "yx",
        mouseWheel: {
            enable: !0,
            axis: e,
            preventDefault: !0
        }
    })), $("body").on("click", "#top-search > a", function (a) {
        a.preventDefault(), $("#header").addClass("search-toggled"), $("#top-search-wrap input").focus()
    }), $("body").on("click", "#top-search-close", function (a) {
        a.preventDefault(), $("#header").removeClass("search-toggled")
    }), $("body").on("click", "#add-arsip > a, .add-arsip", function (a) {
        a.preventDefault(), window.location.replace($(this).attr("data-default"))
    }), $("body").on("click", "#menu-trigger, #chat-trigger", function (a) {
        a.preventDefault();
        var t = $(this).data("trigger");
        $(t).toggleClass("toggled"), $(this).toggleClass("open"), $(".sub-menu.toggled").not(".active").each(function () {
            $(this).removeClass("toggled"), $(this).find("ul").hide()
        }), $(".profile-menu .main-menu").hide(), "#sidebar" == t && ($elem = "#sidebar", $elem2 = "#menu-trigger", $("#chat-trigger").removeClass("open"), $("#chat").hasClass("toggled") ? $("#chat").removeClass("toggled") : $("#header").toggleClass("sidebar-toggled")), "#chat" == t && ($elem = "#chat", $elem2 = "#chat-trigger", $("#menu-trigger").removeClass("open"), $("#sidebar").hasClass("toggled") ? $("#sidebar").removeClass("toggled") : $("#header").toggleClass("sidebar-toggled")), $("#header").hasClass("sidebar-toggled") && $(document).on("click", function (a) {
            0 === $(a.target).closest($elem).length && 0 === $(a.target).closest($elem2).length && setTimeout(function () {
                $($elem).removeClass("toggled"), $("#header").removeClass("sidebar-toggled"), $($elem2).removeClass("open")
            })
        })
    }), $("body").on("click", ".sub-menu > a", function (a) {
        a.preventDefault(), $(this).next().slideToggle(200), $(this).parent().toggleClass("toggled")
    }), $("body").on("click", '[data-clear="notification"]', function (a) {
        a.preventDefault();
        var t = $(this).closest(".listview"),
                e = t.find(".lv-item"),
                i = e.size();
        $(this).parent().fadeOut(), t.find(".list-group").prepend('<i class="grid-loading hide-it"></i>'), t.find(".grid-loading").fadeIn(1500);
        var o = 0;
        e.each(function () {
            var a = $(this);
            setTimeout(function () {
                a.addClass("animated fadeOutRightBig").delay(1e3).queue(function () {
                    a.remove()
                })
            }, o += 150)
        }), setTimeout(function () {
            $("#notifications").addClass("empty")
        }, 150 * i + 200)
    }), $(".dropdown")[0] && ($("body").on("click", ".dropdown.open .dropdown-menu", function (a) {
        a.stopPropagation()
    }), $(".dropdown").on("shown.bs.dropdown", function (a) {
        $(this).attr("data-animation") && ($animArray = [], $animation = $(this).data("animation"), $animArray = $animation.split(","), $animationIn = "animated " + $animArray[0], $animationOut = "animated " + $animArray[1], $animationDuration = "", $animArray[2] ? $animationDuration = $animArray[2] : $animationDuration = 500, $(this).find(".dropdown-menu").removeClass($animationOut), $(this).find(".dropdown-menu").addClass($animationIn))
    }), $(".dropdown").on("hide.bs.dropdown", function (a) {
        $(this).attr("data-animation") && (a.preventDefault(), $this = $(this), $dropdownMenu = $this.find(".dropdown-menu"), $dropdownMenu.addClass($animationOut), setTimeout(function () {
            $this.removeClass("open")
        }, $animationDuration))
    })), $("#calendar-widget")[0] && $("#calendar-widget").fullCalendar({
        contentHeight: "auto",
        theme: !0,
        header: {
            right: "",
            center: "prev, title, next",
            left: ""
        },
        defaultDate: "2014-06-12",
        editable: !0,
        events: [{
                title: "All Day",
                start: "2014-06-01",
                className: "bgm-cyan"
            }, {
                title: "Long Event",
                start: "2014-06-07",
                end: "2014-06-10",
                className: "bgm-orange"
            }, {
                id: 999,
                title: "Repeat",
                start: "2014-06-09",
                className: "bgm-lightgreen"
            }, {
                id: 999,
                title: "Repeat",
                start: "2014-06-16",
                className: "bgm-lightblue"
            }, {
                title: "Meet",
                start: "2014-06-12",
                end: "2014-06-12",
                className: "bgm-green"
            }, {
                title: "Lunch",
                start: "2014-06-12",
                className: "bgm-cyan"
            }, {
                title: "Birthday",
                start: "2014-06-13",
                className: "bgm-amber"
            }, {
                title: "Google",
                url: "http://google.com/",
                start: "2014-06-28",
                className: "bgm-amber"
            }]
    }), $("#todo-lists")[0] && ($("body").on("click", "#add-tl-item .add-new-item", function () {
        $(this).parent().addClass("toggled")
    }), $("body").on("click", ".add-tl-actions > a", function (a) {
        a.preventDefault();
        var t = $(this).closest("#add-tl-item"),
                e = $(this).data("tl-action");
        "dismiss" == e && (t.find("textarea").val(""), t.removeClass("toggled")), "save" == e && (t.find("textarea").val(""), t.removeClass("toggled"))
    })), $(".auto-size")[0] && autosize($(".auto-size")), $("body").on("click", ".profile-menu > a", function (a) {
        a.preventDefault(), $(this).parent().toggleClass("toggled"), $(this).next().slideToggle(200)
    }), $(".fg-line")[0] && ($("body").on("focus", ".fg-line .form-control", function () {
        $(this).closest(".fg-line").addClass("fg-toggled")
    }), $("body").on("blur", ".form-control", function () {
        var a = $(this).closest(".form-group, .input-group"),
                t = a.find(".form-control").val();
        a.hasClass("fg-float") ? 0 == t.length && $(this).closest(".fg-line").removeClass("fg-toggled") : $(this).closest(".fg-line").removeClass("fg-toggled")
    })), $(".fg-float")[0] && $(".fg-float .form-control").each(function () {
        0 == !$(this).val().length && $(this).closest(".fg-line").addClass("fg-toggled")
    }), $("audio, video")[0] && $("video,audio").mediaelementplayer(), $(".chosen")[0] && $(".chosen").chosen({
        width: "100%",
        allow_single_deselect: !0
    }), $(".input-slider")[0] && $(".input-slider").each(function () {
        var a = $(this).data("is-start");
        $(this).noUiSlider({
            start: a,
            range: {
                min: 0,
                max: 100
            }
        })
    }), $(".input-slider-range")[0] && $(".input-slider-range").noUiSlider({
        start: [30, 60],
        range: {
            min: 0,
            max: 100
        },
        connect: !0
    }), $(".input-slider-values")[0] && ($(".input-slider-values").noUiSlider({
        start: [45, 80],
        connect: !0,
        direction: "rtl",
        behaviour: "tap-drag",
        range: {
            min: 0,
            max: 100
        }
    }), $(".input-slider-values").Link("lower").to($("#value-lower")), $(".input-slider-values").Link("upper").to($("#value-upper"), "html")), $("input-mask")[0] && $(".input-mask").mask(), $(".color-picker")[0] && $(".color-picker").each(function () {
        var a = $(this).closest(".cp-container").find(".cp-value");
        $(this).farbtastic(a)
    }), $(".html-editor")[0] && $(".html-editor").summernote({
        height: 150
    }), $(".html-editor-click")[0] && ($("body").on("click", ".hec-button", function () {
        $(".html-editor-click").summernote({
            focus: !0
        }), $(".hec-save").show()
    }), $("body").on("click", ".hec-save", function () {
        var a, t;
        $(".html-editor-click").code(), $(".html-editor-click").destroy(), $(".hec-save").hide(), a = "Content Saved Successfully!", t = "success", $.growl({
            message: a
        }, {
            type: t,
            allow_dismiss: !1,
            label: "Cancel",
            className: "btn-xs btn-inverse",
            placement: {
                from: "top",
                align: "right"
            },
            delay: 2500,
            animate: {
                enter: "animated bounceIn",
                exit: "animated bounceOut"
            },
            offset: {
                x: 20,
                y: 85
            }
        })
    })), $(".html-editor-airmod")[0] && $(".html-editor-airmod").summernote({
        airMode: !0
    }), $(".date-time-picker")[0] && $(".date-time-picker").datetimepicker(), $(".time-picker")[0] && $(".time-picker").datetimepicker({
        format: "LT"
    }), $(".date-picker")[0] && $(".date-picker").datetimepicker({
        format: "DD/MM/YYYY"
    }), $(".form-wizard-basic")[0] && $(".form-wizard-basic").bootstrapWizard({
        tabClass: "fw-nav",
        nextSelector: ".next",
        previousSelector: ".previous"
    }), Waves.attach(".btn:not(.btn-icon):not(.btn-float)"), Waves.attach(".btn-icon, .btn-float", ["waves-circle", "waves-float"]), Waves.init(), $(".lightbox")[0] && $(".lightbox").lightGallery({
        enableTouch: !0
    }), $("body").on("click", ".a-prevent", function (a) {
        a.preventDefault()
    }), $(".collapse")[0] && ($(".collapse").on("show.bs.collapse", function (a) {
        $(this).closest(".panel").find(".panel-heading").addClass("active")
    }), $(".collapse").on("hide.bs.collapse", function (a) {
        $(this).closest(".panel").find(".panel-heading").removeClass("active")
    }), $(".collapse.in").each(function () {
        $(this).closest(".panel").find(".panel-heading").addClass("active")
    })), $('[data-toggle="tooltip"]')[0] && $('[data-toggle="tooltip"]').tooltip(), $('[data-toggle="popover"]')[0] && $('[data-toggle="popover"]').popover(), $(".on-select")[0]) {
        var i = ".lv-avatar-content input:checkbox",
                o = $(".on-select").closest(".lv-actions");
        $("body").on("click", i, function () {
            $(i + ":checked")[0] ? o.addClass("toggled") : o.removeClass("toggled")
        })
    }
    if ($("#ms-menu-trigger")[0] && $("body").on("click", "#ms-menu-trigger", function (a) {
        a.preventDefault(), $(this).toggleClass("open"), $(".ms-menu").toggleClass("toggled")
    }), $(".login-content")[0] && ($("html").addClass("login-content"), $("body").on("click", ".login-navigation > li", function () {
        var a = $(this).data("block");
        $(this).closest(".lc-block").removeClass("toggled"), setTimeout(function () {
            $(a).addClass("toggled")
        })
    })), $('[data-action="fullscreen"]')[0]) {
        var l = $("[data-action='fullscreen']");
        l.on("click", function (a) {
            var t;
            a.preventDefault(), (t = document.documentElement).requestFullscreen ? t.requestFullscreen() : t.mozRequestFullScreen ? t.mozRequestFullScreen() : t.webkitRequestFullscreen ? t.webkitRequestFullscreen() : t.msRequestFullscreen && t.msRequestFullscreen(), l.closest(".dropdown").removeClass("open")
        })
    }
    $('[data-action="clear-localstorage"]')[0] && $('[data-action="clear-localstorage"]').on("click", function (a) {
        a.preventDefault(), swal({
            title: "Apakah anda yakin?",
            text: "Semua yang tersimpan di penyimpanan lokal akan dihapus",
            type: "warning",
            showCancelButton: !0,
            cancelButtonText: "Batalkan",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, hapus semua!",
            closeOnConfirm: !1
        }, function () {
            localStorage.clear(), swal("Selesa!", "penyimpanan lokal telah dibersihkan", "sukses")
        })
    });
    if ($("[data-pmb-action]")[0] && $("body").on("click", "[data-pmb-action]", function (a) {
        a.preventDefault();
        var t = $(this).data("pmb-action");
        "edit" === t && $(this).closest(".pmb-block").toggleClass("toggled"), "reset" === t && $(this).closest(".pmb-block").removeClass("toggled")
    }), $("html").hasClass("ie9") && $("input, textarea").placeholder({
        customClass: "ie9-placeholder"
    }), $(".lvh-search-trigger")[0] && ($("body").on("click", ".lvh-search-trigger", function (a) {
        a.preventDefault(), x = $(this).closest(".lv-header-alt").find(".lvh-search"), x.fadeIn(300), x.find(".lvhs-input").focus()
    }), $("body").on("click", ".lvh-search-close", function () {
        x.fadeOut(300), setTimeout(function () {
            x.find(".lvhs-input").val("")
        }, 350)
    })), $('[data-action="print"]')[0] && $("body").on("click", '[data-action="print"]', function (a) {
        a.preventDefault(), window.print()
    }), $(".typeahead")[0]) {
        var s = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.whitespace,
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            local: ["Alabama", "Alaska", "Arizona", "Arkansas", "California", "Colorado", "Connecticut", "Delaware", "Florida", "Georgia", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky", "Louisiana", "Maine", "Maryland", "Massachusetts", "Michigan", "Minnesota", "Mississippi", "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey", "New Mexico", "New York", "North Carolina", "North Dakota", "Ohio", "Oklahoma", "Oregon", "Pennsylvania", "Rhode Island", "South Carolina", "South Dakota", "Tennessee", "Texas", "Utah", "Vermont", "Virginia", "Washington", "West Virginia", "Wisconsin", "Wyoming"]
        });
        $(".typeahead").typeahead({
            hint: !0,
            highlight: !0,
            minLength: 1
        }, {
            name: "states",
            source: s
        })
    }
    if ($(".wcc-toggle")[0]) {
        $("body").on("click", ".wcc-toggle", function () {
            $(this).parent().html('<div class="wcc-inner"><textarea class="wcci-text auto-size" placeholder="Write Something..."></textarea></div><div class="m-t-15"><button class="btn btn-sm btn-primary">Post</button><button class="btn btn-sm btn-link wcc-cencel">Cancel</button></div>'), autosize($(".auto-size"))
        }), $("body").on("click", ".wcc-cencel", function (a) {
            a.preventDefault(), $(this).closest(".wc-comment").find(".wcc-inner").addClass("wcc-toggle").html("Write Something...")
        })
    }
    $("body").on("click", "[data-skin]", function () {
        $("[data-current-skin]").data("current-skin");
        var a = $(this).data("skin");
        $("[data-current-skin]").attr("data-current-skin", a)
    }), $("form#cfrm").submit(function () {
        var a = window.location,
                t = a.pathname.split("/"),
                e = "http://" + a.hostname + "/" + t[1] + "/";
        console.log(e);
        var i = e + "cari/",
                o = $("#cfrm").serialize();
        return $.ajax({
            url: i,
            type: "POST",
            data: o,
            dataType: "html",
            beforeSend: function () {
                $(".content .container").isLoading({
                    text: "Proses Cari",
                    position: "overlay",
                    tpl: '<span class="isloading-wrapper %wrapper%">%text%<div class="preloader pls-amber" style="position: absolute; top: 0px; left: -40px;"><svg class="pl-circular" viewBox="25 25 50 50"><circle class="plc-path" cx="50" cy="50" r="20"></circle></svg></div>'
                })
            },
            success: function (a) {
                setTimeout(function () {
                    $(".container").html("").html(a), $("#header").removeClass("search-toggled"), $(".content .container").isLoading("hide")
                }, 1e3)
            },
            error: function () {
                setTimeout(function () {
                    $("#header").removeClass("search-toggled"), $(".content .container").isLoading("hide")
                }, 1e3)
            }
        }), !1
    })
});