import react from "react";

import {Button} from "reactstrap";

class App extends react.Component {

    render () {
        return (
            <div className='container'>
                <Button color='success'>成功</Button>
                <Button color='danger'>凶险</Button>
                <Button color='success'>成功</Button>
                <Button color='success'>成功</Button>
                <Button color='success'>成功</Button>
                <Button color='success'>成功</Button>
                <Button color='blue'>凶险</Button>
                <Button color='blue'>凶险111</Button>
            </div>
        );
    }

}


module.exports = App;