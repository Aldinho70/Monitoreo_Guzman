export default class ApiService {

    constructor(baseUrl) {
        this.baseUrl = baseUrl;
    }

    async get(endpoint) {

        const response = await fetch(`${this.baseUrl}${endpoint}`);

        if (!response.ok) {
            throw new Error(`Error HTTP ${response.status}`);
        }

        return response.json();
    }

    async post(endpoint, body) {

        const response = await fetch(`${this.baseUrl}${endpoint}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(body)
        });

        if (!response.ok) {
            throw new Error(`Error HTTP ${response.status}`);
        }

        return response.json();
    }

}