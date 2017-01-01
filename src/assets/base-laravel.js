"use strict";

var application = new Base();

function Base () {
    this.requestAjax = function (action, params, callbacks) {
        return new BaseRequestAjax(action, params, callbacks);
    };

    this.modal = function (title, content, footer) {
        return new BaseModal(title, content, footer);
    };

    this.errorForm = function (errors, formId)
    {
        if (typeof errors !== 'object') {
            return this;
        }
        formId = undefined == formId ? '' : '#' + formId + ' ';

        $.each(errors, function (index, value) {
            var listError = '';

            $.each(value, function (key, message) {
                listError += '<span>' + message + '</span>';
            });

            $(formId + '#' + index).parents('.form-group')
                .addClass('has-error')
                .append('<div class="list-error">' + listError + '</div>');
        })
    };
}

function BaseRequestAjax (action, params, callbacks)
{
    var self = this,
        optionsAjax = {
            url: '',
            data: '',
            method: 'GET',
            success: function (data, textStatus, jqXHR) {},
            beforeSend: function (jqXHR, settings) {},
            complete: function (jqXHR, textStatus) {},
            error: function (jqXHR, textStatus, errorThrown) {}
        },
        initOptionsAjax = function () {
            optionsAjax.url = self.action;
            optionsAjax.method = self.method;
            optionsAjax.data = self.params;

            if (typeof self.callbacks == 'object') {
                $.each(self.callbacks, function (index, value) {
                    if (typeof value == 'function') {
                        optionsAjax[index] = value;
                    }
                });
            }
        };

    this.action = action;

    this.method = 'GET';

    this.params = params;

    this.callbacks = callbacks;

    this.send = function (method, options)
    {
        this.method = undefined === method ? this.method : method;
        initOptionsAjax();
        this.setOptions(options);

        $.ajax(optionsAjax);

        return this;
    };

    this.setOptions = function (options) {
        if (undefined === options) {
            return this;
        }

        $.each(options, function (index, value) {
            optionsAjax[index] = value;
        });

        return this;
    };
}

function BaseModal (title, content, footer)
{
    var self = this,
        _id = '#modal-default',
        _title = title,
        _content = content,
        _footer = footer,
        _setModal = function () {
            var $modal = $(_id);
            $modal.find('#modal-title').html(_title);
            $modal.find('.modal-body').html(_content);
            $modal.find('.modal-footer').html(_footer);
        };

    this.open = function () {
        _setModal();
        $(_id).modal();
        return this;
    };

    this.close = function () {
        $(_id + ' .modal-header button').trigger('click');
        return this;
    };

    this.id = function (id) {
        if (undefined == id) {
            return _id;
        }

        _id = id;
        return this;
    };

    this.title = function (title) {
        if (undefined == title) {
            return _title;
        }

        _title = title;
        return this;
    };

    this.content = function (content) {
        if (undefined == content) {
            return _content;
        }

        _content = content;
        return this;
    };

    this.footer = function (footer) {
        if (undefined == footer) {
            return _footer;
        }

        _footer = footer;
        return this;
    };
}

function BaseEvents()
{
    var _responseSuccess = function ($this, response) {
            toastr.success(
                response.message == undefined ? 'Ação realizada.' : response.message,
                'Sucesso!'
            );

            if ($this.attr('data-target')) {
                $($this.attr('data-target')).trigger('click');
            }

            if ($this.hasClass('close-modal')) {
                application.modal().close();
            }

            return true;
        },
        _responseError = function ($this, response) {
            toastr.warning(
                response.message == undefined
                    ? 'Não consegui concluir a ação, por favor tente novamente.'
                    : response.message,
                'Ação não realizada!'
            );

            return false;
        };

    this.submitAjax = (function () {
        var $this,
            ajaxCallbacks = {
                success: function (response) {
                    if (response === true || (response.status !== undefined && response.status)) {
                        return _responseSuccess($this, response)
                    }

                    return _responseError($this, response);
                },
                error: function (jqXHR) {
                    application.errorForm(jqXHR.responseJSON)
                }
            },
            onSubmit = function (event) {
                event.preventDefault();
                $this = $(this);
                application
                    .requestAjax(
                        $this.attr('action'),
                        $this.serialize()
                    )
                    .send($this.attr('method'), ajaxCallbacks)
            };

        $('body').on('submit', '.submit.ajax', onSubmit);

    })();

    this.ajax = (function () {
        var $this,
            ajaxCallbacks = {
                success: function (response) {
                    if (response === true || (response.status !== undefined && response.status == true)) {
                        return _responseSuccess($this, response)
                    }

                    return _responseError($this, response);
                }
            },
            onClick = function (event) {
                event.preventDefault();
                $this = $(this);
                application.requestAjax($this.attr('href'))
                    .send('GET', ajaxCallbacks);
            };

        $('body').on('click', '.click.ajax', onClick);

    })();

    this.modal = (function () {
        var modal = application.modal(),
            $this,
            title,
            ajaxCallbacks = {
                success: function (response) {
                    modal.content(response)
                        .open();
                },
                error: function (response) {
                    if (response.status == 401) {
                        modal.content(response.responseText)
                            .open();
                    }
                }
            },
            onClick = function (event) {
                event.preventDefault();
                $this = $(this);

                if ((title = $this.attr('title')) || (title = $this.attr('data-title'))) {
                    modal.title(title);
                }

                application.requestAjax($this.attr('href'))
                    .send('GET', ajaxCallbacks);
            };

        $('body').on('click', '.click-modal', onClick);
    })();

    this.clickTabs = (function () {
        var $this,
            callbacks = {
                success: function (response) {
                    $($this.attr('href')).html(response);
                },
                error: function (response) {
                    if (response.status == 401) {
                        $($this.attr('href'))
                            .html('<div class="row"><div class="col-xs-12">' + response.responseText + '</div></div>');
                    }
                }
            },
            navClick = function () {
                $this = $(this);

                if (undefined === $this.data('href')) {
                    return true;
                }

                application
                    .requestAjax($this.data('href'), [], callbacks)
                    .send();
            };

        $('body').on('click', '.nav li a', navClick);
    })();

    this.autocompleteButton = (function () {
        var $this,
            onClick = function () {
                $this = $(this);

                if ($this.find('.btn-default').hasClass('disabled')) {
                    return false;
                }

                $this
                    .parents('.typeahead__field')
                    .find('.typeahead')
                    .trigger('input.typeahead')
                    .trigger('focus.typeahead');
            };

        $('body').on('click', '.typeahead__button', onClick);
    })();
}

$(document).ready(function () {
    new BaseEvents();
});

$.ajaxSetup({
    headers: {
        'X-CSRF-Token': $('input[name="_token"]').val()
    }
});