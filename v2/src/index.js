import './main.min.css';
import './index.css';
import './assets/rc-tree.css';
import registerServiceWorker from './registerServiceWorker';
import AppRoute from './routes/routes.js';

AppRoute.dispatch();

registerServiceWorker();
