import React from 'react';
import ReactDOM from 'react-dom';
import UserService from '../services/user-service.js';
import Aviator from 'aviator';

class LoginComponent extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            username: '',
            password: '',
        }
    }

    onLogin() {
        let {username, password} = this.state;
        if(username.length < 2 || password.length < 6){
            return;
        }
        console.log('start to login service', username, password);
        UserService.login(username, password)
            .then(data=>{
                console.log(data);
                Aviator.navigate('/manage/realStation');
            })
            .catch(e => {
                console.log(e)
            })
    }

    handleChangeUserName(event) {
        this.setState({username: event.target.value});
    }

    handleChangeUserPass(event) {
        this.setState({password: event.target.value});
    }

    render() {
        return (
            <div className="login-bg" id="login">
                <div className="login-wrap">
                    <div className="login-cont">
                        <ul className="login-layout">
                            <li className="name">
                                <input type="text" key="username" value={this.state.username} onChange={this.handleChangeUserName.bind(this)} name="name" autoComplete="off" className="name" maxLength="20" />
                            </li>
                            <input type="hidden" />
                            <li className="mima">
                                <input type="password" key="password"  value={this.state.password} onChange={this.handleChangeUserPass.bind(this)} autoComplete="no" className="password" maxLength="20" />
                            </li>
                            <li className="subbtn">
                                <input type="submit" name="sub" id="submitBtn" value="" onClick={this.onLogin.bind(this)} />
                                {/* <a href="javascript:void(0)" className="forget">忘记密码？</a> */}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        )
    }
}

class LoginController {
    index(request) {
        ReactDOM.render(
            <LoginComponent />,
            document.querySelector('#root')
        )
    }
}
const Login = new LoginController();
export default Login;
