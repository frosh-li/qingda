import './main.min.css';
import './index.css';
import registerServiceWorker from './registerServiceWorker';
import AppRoute from './routes/routes.js';

AppRoute.dispatch();

registerServiceWorker();
