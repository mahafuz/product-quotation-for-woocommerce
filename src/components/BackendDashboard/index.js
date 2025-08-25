import Quotations from "@Containers/Quotations";
import Addons from "@Containers/Addons";

import Navbar from "@Components/Navbar";
import Settings from "@Components/Settings";
import PopupNotification from "@Components/PopupNotification";

import { useQuery } from "@Utils/helper";
import { Container } from "@chakra-ui/react";

const renderSwitch = (page, id, action, path) => {
    switch (page) {
        case "pqfw-product-quotations":
            return <Quotations />;
        case "pqfw-product-quotations-addons":
            return <Addons />;
        case "pqfw-product-quotations-tools":
            return <h1>Tools</h1>;
        case "pqfw-product-quotations-settings":
            return <Settings />;
        case "pqfw-product-quotations-help":
            return <h1>Help</h1>;
        default:
    }
};

export default function BackendDashboard() {
    const query = useQuery();
    return (
        <>
            <Navbar />
            <PopupNotification icon={false} hideProgressBar={true} />
            <Container fluid maxW={`95%`}>
                {renderSwitch(
                    query.get("page"),
                    parseInt(query.get("id")),
                    query.get("action"),
                    query.get("path")
                )}
            </Container>
        </>
    );
}
