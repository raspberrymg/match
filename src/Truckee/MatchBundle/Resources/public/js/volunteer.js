/*
 * This file is part of the Truckee\Volunteer package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$(document).ready(function () {
    $('.carousel').carousel({interval: 7000});

    $("#staff_registration_organization_orgName").change(function () {
        var orgNotice = $("#orgNotice");
        $(orgNotice).hide();
        var orgForm = $("#orgForm");
        var org = $("#staff_registration_organization_orgName");
        var name = $(org).val();
        if ($(org).val() !== '') {
            var where = $(location).attr('pathname');
            var url = where.replace('register/staff', 'nameCheck/' + name);
            $.get(url, function (data) {
                if (0 !== data) {
                    $(orgNotice).show();
                    $(orgNotice).html(data);
                    $(orgForm).hide();
                } else {
                    $(orgNotice).hide();
                }
            });
        }
    });

    $(document).on("click", "#vol_email_selectAll", function () {
        if ($("#vol_email_selectAll").prop("checked")) {
            $("input[type='checkbox']").prop("checked", true);
        } else {
            $("input[type='checkbox']").prop("checked", false);
        }
    });

    $(document).on("click", "#emailOrganization", function () {
        var where = $(location).attr('pathname');
        var id = $(this).attr("value");
        var url = where.replace('search', 'oppForm/' + id);
        $.get(url, function (data) {
            $('#dialog').dialog();
            $('#dialog').dialog({
                modal: true,
                buttons: [
                    {
                        text: "Send",
                        id: "send",
                        class: "btn-xs btn-primary",
                        click: function () {
                            var formData = $("form").serialize();
                            $.post(url, formData, function (response) {
                                if (response.indexOf("Email sent") >= 0) {
                                    $("#send").hide();
                                }
                                $('#dialog').html(response);
                            })
                        }
                    },
                    {
                        text: 'Close',
                        id: "close",
                        class: "btn-xs btn-primary",
                        click: function () {
                            $(this).dialog("close");
                        }
                    }
                ],
                resizable: true,
            });
            $('#dialog').dialog("widget").find(".ui-dialog-titlebar").hide();
            $('#dialog').html(data);
        });
    });
});

function volhelp() {
    var where = $(location).attr('pathname');
    var url = where.replace('register/volunteer', 'volunteer_help');
    $.get(url, function (data) {
        $('#dialog').dialog();
        $('#dialog').dialog("widget").find(".ui-dialog-titlebar-close").hide();
        $('#dialog').dialog({
            modal: true,
            title: 'Volunteer registration help',
            buttons: [
                {
                    text: 'Close',
                    class: "btn-xs btn-primary",
                    click: function () {
                        $(this).dialog("close");
                    }
                }
            ]
        });
        $('#dialog').html(data);
    });
}

function orghelp() {
    var where = $(location).attr('pathname');
    var url = where.replace('register/staff', 'org_help');
    $.get(url, function (data) {
        $('#dialog').dialog();
        $('#dialog').dialog("widget").find(".ui-dialog-titlebar-close").hide();
        $('#dialog').dialog({
            modal: true,
            title: 'Staff registration help',
            buttons: [
                {
                    text: 'Close',
                    class: "btn-xs btn-primary",
                    click: function () {
                        $(this).dialog("close");
                    }
                }
            ]
        });
        $('#dialog').html(data);
    });
}

function fnOrgNotListed(orgName) {
    var orgNotListed = $("#orgNotListed").is(":checked");
    var orgNotice = $("#orgNotice");
    var orgForm = $("#orgForm");
    var org = $("#staff_registration_organization_orgName");

    if (orgNotListed) {
        $(orgNotice).hide();
        $(orgForm).show();
        $(org).val(orgName);
    }

}

