import React, { useContext, useEffect, useState } from "react";
import { Token }                                  from "../models/Token";
import { decode_jwt }                             from "../utils/decode_jwt";
import useAsyncEffect                             from "use-async-effect";

export type Auth = {
    token: String|null;
    tokenData: Token|null;
    refreshToken: String|null;
};

export type AuthCtx = Auth & {
    login: (username: string, password: string) => void;
    logout: () => void;
    isAuthenticated: () => boolean;
};

const initialState: Auth = {
    token: null,
    tokenData: null,
    refreshToken: null,
};

const AuthContext = React.createContext<AuthCtx>({
    ...initialState,
    login: (username, password) => {},
    logout: () => {},
    isAuthenticated: () => false,
});

export function AuthProvider({children}: {children: React.ReactNode}) {
    const [state, setState] = useState<Auth>(initialState);

    const refresh = async () => {
        const request = await fetch('/api/auth/refresh', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 'refresh_token': state.refreshToken}),
        });

        if (request.status !== 200) {
            setState({...state, token: null, tokenData: null, refreshToken: null});

            localStorage.removeItem('token');
            localStorage.removeItem('refresh_token');

            return;
        }

        const resp = await request.json();
        const token = resp.token;
        const refreshToken = resp.refresh_token;

        localStorage.setItem('token', token);
        localStorage.setItem('refresh_token', refreshToken);

        const tokenData = decode_jwt(token);

        setState({...state, token, refreshToken, tokenData})
    };

    useEffect(() => {
        const token = localStorage.getItem('token');
        const refreshToken = localStorage.getItem('refresh_token');

        let tokenData: Token|null = null;
        if (token !== null) {
            tokenData = decode_jwt(token);
        }

        setState({...state, token, refreshToken, tokenData});
    }, []);

    useAsyncEffect(async () => {
        if (!state.token || !state.tokenData) {
            return;
        }

        const diff = Math.round(state.tokenData.exp - ((new Date()).getTime() / 1000));

        // If we have more than a minute left, we wait until 10 seconds
        if (diff > 60) {
            // 10 seconds before the expiration we renew it
            const timeout = setTimeout(async () => {
                await refresh();
            }, (diff - 10) * 1000);

            return () => clearTimeout(timeout);
        }

        // If we have less than 60 seconds, we can renew it immediately
        await refresh();
    }, [state.token, state.tokenData])


    const login = async (username: string, password: string) => {
        const request = await fetch('/api/auth/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({username, password}),
        })

        const resp = await request.json()
        if (request.status === 401) {
            throw { Code: 401, Message: 'Invalid credentials' };
        }

        if (request.status !== 200) {
            throw { Code: request.status, Message: resp.message };
        }

        localStorage.setItem('token', resp.token);
        localStorage.setItem('refresh_token', resp.refresh_token);

        const tokenData = decode_jwt(resp.token);

        setState({...state, token: resp.token, refreshToken: resp.refresh_token, tokenData})
    };

    const isAuthenticated = (): boolean => {
        return !!state.token;
    }

    const logout = () => {
        localStorage.removeItem('token');
        localStorage.removeItem('refresh_token');

        setState({...state, token: null, refreshToken: null, tokenData: null});
    };

    return <AuthContext.Provider value={{
        ...state,
        login,
        logout,
        isAuthenticated,
    }}>
        {children}
    </AuthContext.Provider>
}

export function useAuth() {
    return useContext<AuthCtx>(AuthContext);
}