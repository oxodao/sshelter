import {RequestOptions} from "https";
import {useAuth}        from "./AuthProvider";

export const useApi = () => {
    const auth = useAuth();

    return async function api(url: string, options?: RequestOptions): Promise<any> {
        const headers = {
            'Content-Type': 'application/ld+json',
            'Accept': 'application/ld+json',
            ...options?.headers,
            'Authorization': 'Bearer ' + auth.token,
        };

        const response = await fetch(url, {
            ...options,
            headers,
        });

        if (response.status === 401) {
            auth.logout();
            return;
        }

        if (response.status === 403) {
            throw new Error('Forbidden');
        }

        // @TODO: process errors

        return await response.json();
    }
}