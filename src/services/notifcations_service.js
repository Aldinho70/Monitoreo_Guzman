import ApiService from './apiService.js';

export default class NotificationsService {
    constructor() {
        this.notifications = [];
        this.ApiService = new ApiService('http://ws4cjdg.com/JDigitalReportsV2/src/api/routes/');
    }

    async attendNotification(payload) {
        return await this.ApiService.post('notifications/attendNotification.php', payload);
    }
}