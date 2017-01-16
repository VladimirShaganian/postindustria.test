/**
 * Created by Vladimir on 14.01.2017.
 */

$(function () {
    var url             = '/rest_api/1.0/';
    var fields          = ['name', 'quota', 'email', 'company' ];
    var usersTab        = $('[data-type=users]');
    var abusesTab       = $('[data-type=abuses]');
    var companiesTable  = $('#companiesTable').find('tbody');
    var usersTable      = $('#usersTable').find('tbody');
    var abusesTable     = $('#abusesTable').find('tbody');
    var addCompanyBtn   = $('#addCompany');
    var addUserBtn      = $('#addUser');
    var addCompanyForm  = $('#addCompanyForm');
    var addUserForm     = $('#addUserForm');
    var monthList       = $('#monthList');
    var loader          = $('.loader');
    var generateData    = $('#generateData');
    var showReport      = $('#showReport');
    var cancelAddCompany= $('#cancelAddCompany');
    var cancelAddUser   = $('#cancelAddUser');
    var companiesRowTpl = '<tr data-id="[[id]]"><td>[[number]]</td><td class="name">[[name]]</td><td class="quota">[[quota]]</td><td>' +
                          '<a href="#" class="edit">Edit</a> <a href="#" class="remove">Remove</a>' +
                          '<a href="#" class="save">Save</a> <a href="#" class="cancel">Cancel</a></td></tr>';
    var usersRowTpl     = '<tr data-id="[[id]]">' +
                          '<td>[[number]]</td><td class="name">[[name]]</td><td class="email">[[email]]</td>' +
                          '<td class="company" data-company-id="[[company_id]]" data-company-name="[[company_name]]">[[company_name]]</td><td>' +
                          '<a href="#" class="edit">Edit</a> <a href="#" class="remove">Remove</a>' +
                          '<a href="#" class="save">Save</a> <a href="#" class="cancel">Cancel</a></td></tr>';
    var abusesRowTpl    = '<tr data-id=""><td>[[number]]</td><td><div class="company-name">[[company]]</div></td>' +
                          '<td><div class="company-quota">[[transfered_total]]</div></td><td><div class="company-quota">[[quota]]</div></td></tr>';
    
    // Main handler
    var siteHandler = {
        renderPage: function() {
            loader.show();
            $.ajax({method: "GET", dataType: 'json', url: url + 'companies',
                success: function(resp) {
                    loader.hide();
                    
                    if (resp.data.length == 0) {
                        companiesTable.append('<td colspan="4" class="company-add-info"><br><div class="alert alert-info" role="alert">Please add company</div></td>');
                    } else {
                        var tData = '';
                        var items = [];
                        
                        $.each(resp.data, function(i, v) {
                            tData += companiesRowTpl
                                .replace('[[id]]', v.id)
                                .replace('[[name]]', v.name)
                                .replace('[[quota]]', v.quota)
                                .replace('[[number]]', i + 1);
                        });
                        
                        companiesTable.append(tData);
                        usersTab.css('display', 'block');
                        abusesTab.css('display', 'block');
                        
                        for (var i=0; i < resp.data.length; i++) {
                            items[i] = {
                                id: resp.data[i].id,
                                text: resp.data[i].name
                            }
                        }
                        
                        $('#userCompany').select2({data: items});
                    }
                }
            });
        },
        renderUsers: function() {
            loader.show();
            usersTable.empty();
            $.ajax({method: "GET", dataType: 'json', url: url + 'users',
                success: function(resp) {
                    loader.hide();
                    var tData = '';
                    
                    $.each(resp.data, function(i, v) {
                        tData += usersRowTpl
                            .replace('[[id]]', v.id)
                            .replace('[[name]]', v.name)
                            .replace('[[email]]', v.email)
                            .replace('[[company_name]]', v.company_name)
                            .replace('[[company_name]]', v.company_name)
                            .replace('[[company_id]]', v.company_id)
                            .replace('[[number]]', i + 1);
                    });
                    
                    usersTable.append(tData);

                    $.ajax({
                        method: "GET",
                        dataType: 'json',
                        url: url + 'companies',
                        success: function(resp) {
                            var items = [];
                            for (var i=0; i < resp.data.length; i++) {
                                items[i] = {
                                    id: resp.data[i].id,
                                    text: resp.data[i].name
                                }
                            }
                            $('#userCompany').select2({data: items});
                        }
                    });
                }
            });
        },
        addCompany: function (form) {
            loader.show();
            $.ajax({
                method: "POST",
                dataType: 'json',
                url: url + 'companies',
                data: form,
                success: function(resp) {
                    loader.hide();
                    var v = resp.data;
                    var last = companiesTable.find('tr').last().find('td').first().html();
                    if (last) {
                        last = parseInt(last) + 1;
                    } else {
                        last = 0;
                        $('.company-add-info').remove();
                    }

                    companiesTable.append(companiesRowTpl
                        .replace('[[id]]', v.id)
                        .replace('[[name]]', v.name)
                        .replace('[[quota]]', v.quota)
                        .replace('[[number]]', last));

                    addCompanyForm.hide().find('input').val('');
                    addCompanyBtn.show();
                    usersTab.css('display', 'block');
                    abusesTab.css('display', 'block');
                }
            });
        },
        addUser: function (form) {
            loader.show();
            $.ajax({method: "POST", dataType: 'json', url: url + 'users', data: form,
                success: function(resp) {
                    loader.hide();
                    var v = resp.data;
                    var last = usersTable.find('tr').last().find('td').first().html();

                    last = last ? parseInt(last) + 1 : 0;
                    usersTable.append(usersRowTpl
                        .replace('[[id]]', v.id)
                        .replace('[[name]]', v.name)
                        .replace('[[email]]', v.email)
                        .replace('[[company_name]]', v.company_name)
                        .replace('[[company_name]]', v.company_name)
                        .replace('[[company_id]]', v.company_id)
                        .replace('[[number]]', last));

                    addUserForm.hide().find('input').val('');
                    addUserBtn.show();
                }
            });
        },
        removeItem: function(raw, type) {
            loader.show();
            var id = raw.data('id');
            $.ajax({method: "DELETE", dataType: 'json', url: url + type + '/' + id, 
                success: function() { raw.remove();  loader.hide(); }
            });
        },
        editItem: function (raw, type) {
            loader.show();
            var data = {};
            data.id = raw.data('id');
            $.each(raw.find('td'), function () {
                if ($.inArray(this.className, fields) >= 0){
                    if (this.className != 'company') {
                        data[this.className] = $(this).find('input').val();
                    } else {
                        data['company_id'] = $(this).find('select').val();
                    }
                }
            });

            $.ajax({method: "PUT", dataType: 'json', data: data, url: url + type,
                success: function(resp) {
                    loader.hide();
                    var v = resp.data;
                    var tpl = '';
                    var number = raw.find('td').first().html();
                    raw.empty();
               
                    if (type == 'companies') {
                        tpl = companiesRowTpl.replace('<tr data-id="[[id]]">', '').replace('</tr>', '');
                        raw.append(tpl
                            .replace('[[id]]', v.id)
                            .replace('[[name]]', v.name)
                            .replace('[[quota]]', v.quota)
                            .replace('[[number]]', number))
                    } else {
                        tpl = usersRowTpl.replace('<tr data-id="[[id]]">', '').replace('</tr>', '');
                        raw.append(tpl
                            .replace('[[id]]', v.id)
                            .replace('[[name]]', v.name)
                            .replace('[[email]]', v.email)
                            .replace('[[company_name]]', v.company_name)
                            .replace('[[company_name]]', v.company_name)
                            .replace('[[company_id]]', v.company_id)
                            .replace('[[number]]', number));
                    }
                }
            });
        },
        editRaw: function(raw) {
            $.each(raw.find('td'), function () {
                if ($.inArray(this.className, fields) >= 0){
                    var val = this.innerText;
                    if (this.className != 'company') {
                        $(this).empty().append('<input type="text" class="form-control" value="' + val + '">');
                    } else {
                        var that = $(this);
                        $(this).empty().append('<select class="form-control company_name" name="company_id"></select>');
                        $.ajax({method: "GET", dataType: 'json', url: url + 'companies',
                            success: function (data) {
                                var items = [];
                                for (var i=0; i < data.data.length; i++) {
                                    items[i] = {
                                        id: data.data[i].id,
                                        text: data.data[i].name
                                    }
                                }
                                that.find('select').select2({data: items, val: 'asdf'});
                            }
                        });
                    }
                }
            });
            raw.find('.edit, .remove').hide();
            raw.find('.save, .cancel').show();
        },
        cancelEdit: function (raw) {
            $.each(raw.find('td'), function () {
                if ($.inArray(this.className, fields) >= 0){
                    var val = $(this).find('input').val();
                    if (this.className == 'company') {
                        val = $(this).data('company-name');
                    }
                    $(this).empty().append(val);
                }
            });
            raw.find('.edit, .remove').show();
            raw.find('.save, .cancel').hide();
        },
        generateData: function () {
            loader.show();
            $.ajax({
                method: "post",
                dataType: 'json',
                url: url + 'generate',
                success: function (data) {
                    loader.hide();
                    alert('New data is ready.');
                }
            });
        },
        renderReport: function (date) {
            loader.show();
            date = date ? '/?date=' + date : '';
            $.ajax({
                method: "get",
                dataType: 'json',
                url: url + 'report' + date,
                success: function (resp) {
                    loader.hide();
                    abusesTable.empty();
                    $.each(resp.data, function(i, v) {
                        abusesTable.append(abusesRowTpl
                            .replace('[[company]]', v.company_name)
                            .replace('[[transfered_total]]', v.transfered_data)
                            .replace('[[quota]]', v.quota)
                            .replace('[[number]]', i + 1)
                        );
                    });

                }
            });   
        }
    };


    siteHandler.renderPage();
    monthList.select2();
    addCompanyBtn.on('click', function () {addCompanyForm.show(); $(this).hide()});
    addUserBtn.on('click', function () {addUserForm.show(); $(this).hide()});
    usersTab.on('click', function() {siteHandler.renderUsers()});
    generateData.on('click', function() {siteHandler.generateData()});
    showReport.on('click', function () {siteHandler.renderReport(monthList.val())});
    cancelAddCompany.on('click', function () {addCompanyForm.hide(); addCompanyBtn.show()});
    cancelAddUser.on('click', function () {addUserForm.hide(); addUserBtn.show()});
    companiesTable
        .on('click', '.remove', function() {siteHandler.removeItem($(this).closest('tr'), 'companies');})
        .on('click', '.edit', function() {siteHandler.editRaw($(this).closest('tr'));})
        .on('click', '.save', function() {siteHandler.editItem($(this).closest('tr'), 'companies');})
        .on('click', '.cancel', function() {siteHandler.cancelEdit($(this).closest('tr'));});
    usersTable
        .on('click', '.remove', function() {siteHandler.removeItem($(this).closest('tr'), 'users');})
        .on('click', '.edit', function() {siteHandler.editRaw($(this).closest('tr'));})
        .on('click', '.save', function() {siteHandler.editItem($(this).closest('tr'), 'users');})
        .on('click', '.cancel', function() {siteHandler.cancelEdit($(this).closest('tr'));});

    addCompanyForm.validate({
        rules:  {
            name: {required: true, maxlength: 40},
            quota: {required: true, digits: true, maxlength: 10}
        },
        submitHandler: function(form) {
            siteHandler.addCompany($(form).serializeArray());
        }
    });

    addUserForm.validate({
        rules:  {
            name: {required: true, maxlength: 20},
            email: {required: true, email: true, maxlength: 80}
        },
        submitHandler: function(form) {
            siteHandler.addUser($(form).serializeArray());
        }
    });


});
