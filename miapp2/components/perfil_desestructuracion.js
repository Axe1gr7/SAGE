
import { Text, View, Button, StyleSheet } from "react-native";
import React, { useState } from 'react';

export const Perfil = ({ nombre, carrera, materia, cuatri, grupo, style }) => {

    const [mostrar, setmostrar] = useState(false);

    return (
        <View style={[estilose.tarjeta, style]}>
            
            <Text style={estilose.nombre}>{nombre}</Text>
            <Text style={estilose.carrera}>{carrera}</Text>
            
            {mostrar && 
            <>
                <Text style={estilose.otrotexto}>{materia}</Text>
                <Text style={estilose.otrotexto}>{cuatri}</Text>
                <Text style={estilose.otrotexto}>{grupo}</Text>
            </>
            }
            
            <Button 
                title="mostrar perfil"
                onPress={() => setmostrar(!mostrar)}
            />

        </View>
    )
} 

const estilose = StyleSheet.create({
    nombre: {
        fontSize: 24,
        fontWeight: '700',
        textTransform: "uppercase"
    },
    carrera: {
        fontSize: 18,
        color: 'blue',
        fontFamily: 'Roboto' 
    },
    otrotexto: {
        fontSize: 12,
        fontFamily: 'Courier',
        fontStyle: 'italic'
    },
    tarjeta: {
        borderWidth: 3,
        margin: 20
    }
});