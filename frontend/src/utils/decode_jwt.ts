import { Token } from "../models/Token";

export const decode_jwt = (token: string): Token => {
    const splitted = token.split('.');
    if (splitted.length !== 3) {
        throw 'Invalid JWT';
    }

    let data = atob(splitted[1]);

    return JSON.parse(data);
}