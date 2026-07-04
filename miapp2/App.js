/*zona1: importaciones de componentes y archivos */
import { StatusBar } from 'expo-status-bar';
import { StyleSheet, Text, View} from 'react-native';
import MenuScreen from './screens/menu';


/*zona2: main - hogar de los componetes */
export default function App() {
  return (
    <View style={styles.container}>

      <MenuScreen/>
      
    <StatusBar style='auto'/>

    </View>
  );
}

/*zona3: estilos y posicionamiento */
const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#7215d6',
    alignItems: 'center',
    justifyContent: 'center',
    flexDirection:'column',
  },

});