import {Control, Controller} from "react-hook-form";
import {TextField}           from "@mui/material";
import {Machine}             from "../models/Machine";

type TextFieldProps = {
    name: string;
    control: Control<Machine, object>;
    label: string;
    defaultValue: any | undefined;
    [otherProps: string]: any;
};

export default function CustomTextfield({name, control, label, defaultValue, ...otherProps}: TextFieldProps) {
    return <Controller
        control={control}
        defaultValue={defaultValue}
        name={name as any}
        render={({field: {onChange, value, onBlur}}) => <TextField
            label={label}
            onChange={onChange}
            onBlur={onBlur}
            value={value}
            style={{
                marginTop: '.5em',
                marginBottom: '.25em',
            }}
            {...otherProps}
            variant='outlined'
        />}
    />
}