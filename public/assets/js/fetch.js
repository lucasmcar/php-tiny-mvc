var Fetch = (function(){

    function Fetch(baseURL){
        this.baseURL = baseURL;
    }

    Fetch.prototype.get = async function(url, headers, type){
        if(type == "json"){
            return await this.requestJson(url, 'GET', null, headers);
        }
        return await this.request(url, 'GET', null, headers);
    }

    Fetch.prototype.post = async function (url, body, headers = {}, type){
        if(type == "json"){
            return await this.requestJson(url, 'POST', body, headers);
        }
        return await this.request(url, 'POST', body, headers);
    }

    Fetch.prototype.put = async function (url, body, headers = {}, type){
        if(type == "json"){
            return await this.requestJson(url, 'PUT', body, headers);
        }
        return await this.request(url, 'PUT', body, headers);
    }

    Fetch.prototype.delete = async function (url, body, headers = {}, type){
        if(type == "json"){
            return await this.requestJson(url, 'DELETE', null, headers);
        }
        return await this.request(url, 'DELETE', null, headers);
    }



    Fetch.prototype.requestJson = async function(url, method, body = null, headers = {}) {
        const requestOptions = {
            method: method,
            headers: headers,
            body: body ? JSON.stringify(body) : null
        };

        const response = await fetch(this.baseURL + url, requestOptions);
        const responseData = await response.json();
       
        if (!response.ok) {
            throw new Error(`Error ${response.status}: ${responseData.message}`);
        }

        return responseData;
    }

    Fetch.prototype.request = async function(url, method, body = null, headers = {}) {
        const requestOptions = {
            method: method,
            headers: headers,
            body
        };

        const response = await fetch(this.baseURL + url, requestOptions);
        const responseData = await response.text();
       
        if (!response.ok) {
            throw new Error(`Error ${response.status}: ${responseData.message}`);
        }

        return responseData;
    }

    return Fetch;
})();