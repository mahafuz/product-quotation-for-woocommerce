const { render } = wp.element;

import 'react-form-builder2/dist/app.css';

import App from './components/App'
import "./scss/index.scss";

render( <App />, document.getElementById(  'pqfw-form-builder' ) );