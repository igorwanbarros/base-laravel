"use strict";

function Base () {
    this.requestAjax = function (action, params, callbacks) {
        return new BaseRequestAjax(action, params, callbacks);
    }
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
        initOptionsAjax();
        this.method = undefined === method ? this.method : method;
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

var application = new Base();
