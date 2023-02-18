class Cookie
{
    static COOKIE_TTL = 31536000;

    static COOKIE_PATH = '/';

    static SAME_SITE = `Strict`;

    static getCookie(key = '') {
        return document.cookie
            .split('; ')
            .find((row) => row.startsWith(key + '='))
            ?.split('=')[1];
    }

    static setCookie(
        key,
        value,
        maxAge = this.COOKIE_TTL,
        path = this.COOKIE_PATH,
        sameSite = this.SAME_SITE
    ) {
        document.cookie = `${key}=${value};max-age=${maxAge};path=${path};SameSite=${sameSite};`;
    }

    static deleteCookie(
        key,
        path = this.COOKIE_PATH
    ) {
        document.cookie = `${key}=;max-age=0;path=${path};`;
    }
}

class Server
{
    static GET_PARAMS = new Proxy(new URLSearchParams(window.location.search), {
        get: (searchParams, prop) => searchParams.get(prop),
    });

    static authHeader = {
        'Authorization': `Bearer ${Server.getApiToken()}`,
    };

    static contentHeader = {
        'Content-Type': 'application/json'
    };

    static authAndContentHeaders = {
        'Authorization': `Bearer ${Server.getApiToken()}`,
        'Content-Type': 'application/json',
    };

    static authAndContentAndAcceptHeaders = {
        'Authorization': `Bearer ${Server.getApiToken()}`,
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    };

    static getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]').content;
    }

    static getApiToken() {
        return decodeURI(Cookie.getCookie('api_token'));
    }

    static async getData(uri = '', headers = {}, withErrorHandling = true) {
        let url = window.location.origin + uri;
        let parameters = {
            method: 'GET',
            headers: headers,
            credentials: 'include',
        };

        let {response, responseJson} = await this.getResult(url, parameters);

        if (withErrorHandling) {
            let isOkResponse = this.checkResponse(await response, await responseJson);

            if (! Array.isArray(responseJson.data) && typeof responseJson.data !== 'object') {
                isOkResponse = false;

                Toast.showServerError('Incorrect response');
            }

            if (! isOkResponse) {
                return false;
            }
        }

        return responseJson.data;
    }

    static async getResult(url, parameters) {
        let response = await fetch(url, parameters);
        let isJson = response.headers.get('content-type')?.includes('application/json');
        let responseJson = isJson && await response.json();

        return {response, responseJson};
    }

    static async postData(
        uri = '',
        data = {},
        headers = {},
        withErrorHandling = true
    ) {
        if (isEmpty(headers)) {
            headers = Server.contentHeader;
        }

        let url = window.location.origin + uri;
        let parameters = {
            method: 'POST',
            headers: headers,
            body: JSON.stringify(data),
            credentials: 'include',
        };

        let {response, responseJson} = await this.getResult(url, parameters);

        if (withErrorHandling) {
            this.checkResponse(await response, await responseJson);
        }

        return response;
    }

    static async putData(
        uri = '',
        data = {},
        headers = {},
        withErrorHandling = true
    ) {
        if (isEmpty(headers)) {
            headers = Server.authAndContentAndAcceptHeaders;
        }

        let url = window.location.origin + uri;
        let parameters = {
            method: 'PUT',
            headers: headers,
            body: JSON.stringify(data),
            credentials: 'include',
        };

        let {response, responseJson} = await this.getResult(url, parameters);

        if (withErrorHandling) {
            this.checkResponse(await response, await responseJson);
        }

        return response;
    }

    static async deleteData(
        uri = '',
        headers = {},
        withErrorHandling = true
    ) {
        if (isEmpty(headers)) {
            headers = Server.contentHeader;
        }

        let url = window.location.origin + uri;
        let parameters = {
            method: 'DELETE',
            headers: headers,
            body: JSON.stringify({
                '_token': Server.getCsrfToken(),
            }),
            credentials: 'include',
        };

        let {response, responseJson} = await this.getResult(url, parameters);

        if (withErrorHandling) {
            let isOkResponse = this.checkResponse(await response, await responseJson);

            if (isOkResponse) {
                Toast.showToastMessageWithTimeout(
                    'Удалено',
                    'По вашему запросу удалены данные',
                    'success'
                );
            }
        }

        return response;
    }

    static checkResponse(response, responseJson = {}) {
        if (response.ok) {
            return true;
        }

        let error = (responseJson && responseJson.message) || response.status;

        Toast.showServerError(error);

        return false;
    }
}

class Url
{
    static regexOfLocales = /\/(ru|en)\/*/;
    static arrayOfLocales = ['ru', 'en'];

    static getLanguage() {
        let resultOfRegex = Url.regexOfLocales.exec(window.location.href);

        if (resultOfRegex === null) {
            return 'en';
        }

        return window.location.href.substring(resultOfRegex.index + 1, resultOfRegex.index + 3);
    }

    static changeLanguage(language) {
        if (! Url.arrayOfLocales.includes(language)) {
            console.log('Undefined locale in changeLanguage function');

            return false;
        }

        Cookie.setCookie('locale', language);

        if (window.location.href.search(Url.regexOfLocales) === -1) {
            window.location.href = window.location.href.split('#')[0] + language;

            return true;
        }

        let resultOfRegex = Url.regexOfLocales.exec(window.location.href);

        window.location.href =
            window.location.href.substring(0, resultOfRegex.index)
            + '/'
            + language
            + window.location.href.substring(resultOfRegex.index + 3);

        return true;
    }
}

class Storage
{
    static set(
        key,
        value,
        dateTimeWhenExpired = (new DateTime()).addHours(4)
    ) {
        localStorage.setItem(key, JSON.stringify(value));
        localStorage.setItem(
            this.getTTLKeyForCache(key),
            JSON.stringify(dateTimeWhenExpired.getTimeStamp())
        );
    }

    static get(key) {
        if (! this.exists(key)){
            return '';
        }

        return JSON.parse(localStorage.getItem(key));
    }

    static exists(key) {
        let exists = ! Object.is(localStorage.getItem(key), null);

        if (! exists) {
            return exists;
        }

        let expired = this.getDateTimeWhenExpired(key).getTimeStamp() < (new DateTime()).getTimeStamp();

        if (expired === true) {
            this.remove(key);
            exists = false;
        }

        return exists;
    }

    static remove(key) {
        localStorage.removeItem(key);
        localStorage.removeItem(this.getTTLKeyForCache(key));
    }

    static getDateTimeWhenExpired(key) {
        return new DateTime(this.get(this.getTTLKeyForCache(key)));
    }

    static getTTLKeyForCache(key) {
        return key + '_ttl';
    }

    static removeAll() {
        localStorage.clear();
    }
}

class Toast
{
    static showToastMessageWithTimeout(
        title = 'Validation Error',
        message = 'Please check the entered data',
        level = 'message',
        hideAfterSeconds = 5
    ) {
        this.showToastMessage(title, message, level);

        setTimeout(this.hideToastMessage, hideAfterSeconds * 1000);
    }

    static showToastMessage(
        title = 'Validation Error',
        message = 'Please check the entered data',
        level = 'message'
    ) {
        let toastDiv = document.querySelector('.toast');

        toastDiv.querySelector('.toast-level').classList.add('level-' + level);
        toastDiv.querySelector('.toast-title').innerText = title;
        toastDiv.querySelector('.toast-body').innerText = message;

        let bsToast = new bootstrap.Toast(toastDiv);

        bsToast.show();
    }

    static hideToastMessage() {
        let toastDiv = document.querySelector('.toast');
        let bsToast = new bootstrap.Toast(toastDiv);

        bsToast.hide();
    }

    static showServerError(error = '404') {
        this.showToastMessageWithTimeout(
            'Произошла ошибка',
            'Неверный запрос: ' + error,
            'error'
        );
    }
}

class DateTime
{
    dateTime;

    constructor(dateTime = '') {
        if (dateTime === '') {
            this.dateTime = new Date();
        } else {
            this.dateTime = new Date(dateTime);
        }
    }

    getDateObject() {
        return this.dateTime;
    }

    getTimeStamp() {
        return this.dateTime.getTime();
    }

    addMinutes(minutes) {
        this.dateTime.setMinutes(
            this.dateTime.getMinutes() + minutes
        );

        return this;
    }

    addHours(hours) {
        this.addMinutes(hours * 60)

        return this;
    }

    addDays(days) {
        this.addHours(days * 24);

        return this;
    }

    addMonths(months) {
        this.addDays(months * 30)

        return this;
    }

    addYears(years) {
        this.dateTime.setFullYear(
            this.dateTime.getFullYear() + years
        );

        return this;
    }

    toString() {
        return this.dateTime.toString();
    }
}