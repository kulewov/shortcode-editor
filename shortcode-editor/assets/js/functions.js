+function () {

    var selector        = 'js-rb-shortcode',
        totalData       = {},
        requiredFields  = [],
        shortcodeFields = [],
        formObject      = {},
        form,
        shortcode,
        action;

    /**
     * Вставка данных в шаблон
     *
     * @param str
     * @param data
     * @returns {Function}
     */
    function tmpl(str, data) {
        // Figure out if we're getting a template, or if we need to
        // load the template - and be sure to cache the result.
        var fn = !/\W/.test(str) ?
            cache[str] = cache[str] ||
                tmpl(document.getElementById(str).innerHTML) :

            // Generate a reusable function that will serve as a template
            // generator (and which will be cached).
            new Function('obj',
                'var p=[],print=function(){p.push.apply(p,arguments);};' +

                // Introduce the data as local variables using with(){}
                'with(obj){p.push(\'' +

                // Convert the template into pure JavaScript
                str
                    //.toString()
                    .replace(/[\r\t\n]/g, ' ')
                    .split('<%').join('\t')
                    .replace(/((^|%>)[^\t]*)'/g, '$1\r')
                    .replace(/\t=(.*?)%>/g, '\',$1,\'')
                    .split('\t').join('\');')
                    .split('%>').join('p.push(\'')
                    .split('\r').join('\\\'')
                + '\');}return p.join(\'\');');
        // Provide some basic currying to the user
        return data ? fn(data) : fn;
    }

    /**
     * Устанавливает прослушивание события
     *
     * @param eventName
     * @param selector
     * @param func
     */
    function on(eventName, selector, func) {
        document.addEventListener(eventName, function (event) {
            if (event.target.closest(selector) === null) {
                return;
            }

            event.preventDefault();
            func(event);
        });
    }

    /**
     * Скрывает поле и очищает результат поиска.
     *
     * @param event
     */
    function closeSearchField(event) {

        form.form && form.form.classList.remove('active');
        form.input && (form.input.value = '');
        form.result && (form.result.innerHTML = '');

        if (form.tools) {
            form.tools.parentNode.style.paddingTop = parseInt(form.tools.parentNode.style.paddingTop) - 42 + 'px';
            form.tools.style.paddingTop = "";
        }

        clearAll();
    }

    /**
     * Получает базовые элементы формы
     *
     * @returns {{result: *, input: *, form: *, tools: *, group: *}|{}}
     */
    function getShortcodeForm() {
        var form = document.querySelector(`.${selector}`);

        if (!form) {
            return {};
        }

        return {
            'form'  : form,
            'input' : form.querySelector(`.${selector}-input`),
            'group' : form.querySelector(`.${selector}-group`),
            'result': form.querySelector(`.${selector}-result`),
            'tools' : document.querySelector('#wp-content-editor-tools')
        };
    }

    /**
     * Вызов строки поиска.
     */
    on('click', `.${selector}-button`, function (event) {
        clearAll();
        action = event.target.dataset.action;
        shortcode = event.target.dataset.shortcode;
        prepareFields();
        if (!shortcodeFields) {
            return;
        }
        form = getShortcodeForm();
        if (!form.form) {
            return;
        }
        var element = shortcodeFields.shift();
        renderForm(element);
        form.form.classList.add('active');
    });

    function clearAll() {
        totalData = {};
        requiredFields = [];
        shortcodeFields = [];
        formObject = {};
        form = '';
        shortcode = '';
        action = '';
    }

    function prepareFields() {
        formObject = eval(action);
        shortcodeFields = Object.keys(formObject);
        if (formObject['required']) {
            requiredFields = formObject['required'];
            shortcodeFields = shortcodeFields.filter(item => item !== 'required');
        }

    }

    function renderForm(element) {
        var data = formObject[element];
        if (form.input) {
            form.input.focus();
            form.input.name = element;
            form.input.placeholder = data;
            form.input.value = '';
        }
        if (form.tools && !form.form.classList.contains('active')) {
            form.tools.parentNode.style.paddingTop = parseInt(form.tools.parentNode.style.paddingTop) + 42 + 'px';
            form.tools.style.paddingTop = "62px";
        }
    }

    /**
     * Событие скрытия строки ввода.
     */
    on('click', `.${selector}-close`, closeSearchField);

    /**
     * Соибытие на клавишу Enter в поле ввода.
     */
    on('keyup', `.${selector}-input`, function (e) {
        if (e.keyCode === 13) {

            var eTarget = e.target;

            if (requiredFields && requiredFields.includes(eTarget.name) && eTarget.value === '') {
                document.querySelector(`.${selector}`).classList.add('has-error');
                document.querySelector(`.${selector}-error`).classList.remove('is-hidden');
                return;
            }

            if (eTarget.name === 'odds' && !isNumeric(eTarget.value)) {
                document.querySelector(`.${selector}`).classList.add('has-error');
                document.querySelector(`.${selector}-error-num`).classList.remove('is-hidden');
                return;
            }

            if (eTarget.name === 'bookmaker') {
                searchBookmaker(eTarget);
                return;
            }

            totalData[eTarget.name] = eTarget.value;

            if (!shortcodeFields.length) {
                addShortcodeContent();
                return;
            }

            var element = shortcodeFields.shift();
            renderForm(element);
            return;
        }
        document.querySelector(`.${selector}`).classList.remove('has-error');
        document.querySelector(`.${selector}-error`).classList.add('is-hidden');
        document.querySelector(`.${selector}-error-num`).classList.add('is-hidden')
    });

    function isNumeric(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }

    /**
     * Отправляет запрос на полчение списка букмекеров и выводит ответ.
     *
     * @param element
     */
    function searchBookmaker(element) {
        if (element.value.length < 3) {
            return;
        }

        var template         = document.querySelector(`#${selector}-template`),
            templateNoResult = document.querySelector(`#${selector}-template-no-result`);

        form.group && form.group.classList.add('active');
        var params = {
            action: 'rb_shortcode_' + action,
            search: element.value
        };
        fetch(ajaxurl, {
            method : 'POST',
            body   : new URLSearchParams(params),
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'
            },
        })
            .then((response) => {
                return response.json()
            })
            .then((response) => {
                if (!template || !form.result) {
                    form.group && form.group.classList.remove('active');
                    return;
                }
                if (!response.length) {
                    templateNoResult && (form.result.innerHTML = templateNoResult.innerHTML);
                } else {
                    form.result.innerHTML = '';
                    for (var i = 0; i < response.length; i++) {
                        form.result.innerHTML += tmpl(template.innerHTML, response[i]);
                    }
                }
                form.group && form.group.classList.remove('active');
            })
            .catch((error) => {
                console.log(error);
                form.group && form.group.classList.remove('active');
            })
        ;
    }

    /**
     * Событие добавления шорткода с результатом поиска
     */
    on('click', '.js-add-bookmaker-id', function (e) {
        var eTarget = e.target;
        totalData['id'] = eTarget.dataset.id;
        form.result.innerHTML = '';
        if (!shortcodeFields.length) {
            addShortcodeContent();
            return;
        }
        var element = shortcodeFields.shift();
        renderForm(element);
    });

    /**
     * Добавлеяет шорткод ставки в редактор
     *
     */
    function addShortcodeContent() {
        var shortcodeBody = '';
        for (var key in totalData) {
            if (totalData[key]) {
                shortcodeBody += ` ${key}='${totalData[key]}'`;
            }
        }

        wp.media.editor.insert(`[${shortcode}${shortcodeBody}]`);

        closeSearchField();
    }
}();