import Aviator from 'aviator';
import ManageController from '../controllers/manage-controller.js';
import LoginController from '../controllers/login-controller.js';

class Route {
    constructor() {
        Aviator.setRoutes({
            '/': {
                target: LoginController,
                "/": 'index'
            },
            '/manage': {
                target: ManageController,
                "/realStation": 'realStation'
            },
        })
    }

    dispatch() {
        Aviator.dispatch();
    }
}

const AppRoute = new Route();
export default AppRoute;
