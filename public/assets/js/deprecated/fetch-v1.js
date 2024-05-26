/**
 * a utilização dessa classe essa utilização esta obsoleta
 * @deprecated
 */
class Fetch {
    constructor(baseURL) {
        this.baseURL = baseURL;
    }
    async get(url, headers) {
        return await this.request(url, 'GET', null, headers);
    }
    async post(url, body, headers = {}) {
        return await this.request(url, 'POST', body, headers);
    }
    async put(url, body = {}, headers = {}) {
        return await this.request(url, 'PUT', body, headers);
    }
    async delete(url, headers = {}) {
        return await this.request(url, 'DELETE', null, headers);
    }
    async request(url, method, body = null, headers = {}) {
        const requestOptions = {
            method: method,
            headers: headers,
            body: body ? JSON.stringify(body) : null
        };

        const response = await fetch(this.baseURL + decodeURI(url), requestOptions);
        const responseData = await response.json();

        if (!response.ok) {
            throw new Error(`Error ${response.status}: ${responseData.message}`);
        }

        return responseData;
    }
}
