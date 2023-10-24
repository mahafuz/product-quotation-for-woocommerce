import React from "react";
import ReactDOM from "react-dom";
import Settings from "./components/Settings";

import { createRoot } from 'react-dom/client';


import './scss/index.scss';

const container = document.getElementById("pqfw-app");

const root = createRoot(container);
root.render(<Settings />);