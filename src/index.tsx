import React from "react";
import ReactDOM from "react-dom";
import { HashRouter } from 'react-router-dom';
import App from "./components/App";

import './scss/index.scss';

ReactDOM.render(
	<HashRouter>
		<h1 className="pqfw-app-title">Settings</h1>
		<App />
	</HashRouter>,
    document.getElementById("pqfw-app")
);