import { Alert, Button, Paper, Snackbar, TextField } from "@mui/material";
import { useState } from "react";
import { useForm } from "react-hook-form";
import { useAuth } from "../hooks/AuthProvider";

import '../assets/css/login.scss';

type LoginForm = {
    username: string;
    password: string;
}

export default function Login() {
    const auth = useAuth();
    const [errMsg, setErrMsg] = useState<String | null>(null);
    const { register, handleSubmit } = useForm<LoginForm>();

    const login = async (data: LoginForm) => {
        await setErrMsg(null);
        try {
            await auth.login(data.username, data.password);
        } catch (e: any) {
            await setErrMsg(e.Message);
        }
    }

    return <Paper variant="outlined" className="Login">
        <h1>SSHelter</h1>
        <form className="Login__Form" onSubmit={handleSubmit(login)}>
            <TextField
                className="LoginForm__Textfield"
                label="Username"
                variant="outlined"
                InputLabelProps={{ required: false }}
                required
                {...register('username')}
            />

            <TextField
                className="LoginForm__Textfield"
                label="Password"
                variant="outlined"
                type="password"
                InputLabelProps={{ required: false }}
                required 
                {...register('password')}
            />

            <Button className="LoginForm__Button" variant="outlined" type="submit">Login</Button>
        </form>

        <Snackbar open={errMsg !== null} onClose={() => setErrMsg(null)} autoHideDuration={3000} anchorOrigin={{ vertical: 'bottom', horizontal: 'center' }}>
            <Alert severity="error">
                {errMsg}
            </Alert>
        </Snackbar>
    </Paper>
}