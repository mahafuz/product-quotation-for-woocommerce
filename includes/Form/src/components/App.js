import React from 'react';
import { HashRouter as Router, Route, Switch, NavLink } from "react-router-dom";


import Page from './Page';
import Settings from './Settings';


const App = () => (
    <>
        <h2>Form Builder</h2>
        <Page />
    </>
);

export default App;