/*zona1: importaciones de componentes y archivos */
import { StatusBar } from 'expo-status-bar';
import { StyleSheet, Text, View} from 'react-native';
import { Perfil2 } from '../components/perfil2';

/*zona2: main - hogar de los componetes */
export default function TarjetasScreen() {
  return (
    <View style={styles.container}>
      
<Perfil2
        style={styles.tarjetaRoja}
        nombre="axel" 
        carrera="sistemas" 
        materia="pm" 
        cuatri="9no" 
        grupo="206"
    />
    
    <Perfil2
        style={styles.tarjetaVerde}
        nombre="chola" 
        carrera="sistemas" 
        materia="pm" 
        cuatri="9no" 
        grupo="207"
    />

        <Perfil2
        style={styles.tarjetaAul}
        nombre="cholo" 
        carrera="sistemas" 
        materia="pm" 
        cuatri="9no" 
        grupo="207"
    />

    </View>
  );
}

/*zona3: estilos y posicionamiento */
const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
    alignItems: 'center',
    justifyContent: 'center',
    flexDirection:'column',
    marginTop: 40
  },
  tarjetaRoja:{
    backgroundColor:'rgba(193, 0, 0, 0.73)'
  },
  tarjetaVerde:{
    backgroundColor:'rgba(0, 193, 0, 0.73)'
  },
  tarjetaAul:{
    backgroundColor:'rgba(63, 154, 215, 0.73)'
  }
});