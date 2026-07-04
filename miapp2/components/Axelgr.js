import { Text,View } from "react-native";

export const Perfil= (props) =>{
    return(
        <View>
            
            <Text>{props.nombre}</Text>
            <Text>{props.carrera}</Text>
            <Text>{props.materia}</Text>
            <Text>{props.cuatri}</Text>
            <Text>{props.grupo}</Text>
        </View>
    )
} 