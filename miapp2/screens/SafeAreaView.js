import React, { useState } from 'react';
import {
    SafeAreaView,
    ScrollView,
    View,
    Text,
    StyleSheet,
    Switch
} from 'react-native';


export default function SafeAreaScreen(){
  const [activo, setActivo] = useState(true);

  const Contenedor = activo ? SafeAreaView : View;

  return (
    <Contenedor style={styles.fondo}>
      

      <Text style={styles.titulo}>
        safe area contedio y scrollview 
      </Text>
      
      <Text style={styles.descripcion}>
        ueeyyeyuewyeuwyeuwuiegwqiuguieqwgiuewgyewfiewyfeqiygdiuewqduigeqwudigequidequwduiqwgduiqwgduwqgduiwgqdgeqiuhequgeqiugfuieqdubeqd
        ueeyyeyuewyeuwyeuwuiegwqiuguieqwgiuewgyewfiewyfeqiygdiuewqduigeqwudigequidequwduiqwgduiqwgduwqgduiwgqdgeqiuhequgeqiugfuieqdubeqd
        ueeyyeyuewyeuwyeuwuiegwqiuguieqwgiuewgyewfiewyfeqiygdiuewqduigeqwudigequidequwduiqwgduiqwgduwqgduiwgqdgeqiuhequgeqiugfuieqdubeqd
        ueeyyeyuewyeuwyeuwuiegwqiuguieqwgiuewgyewfiewyfeqiygdiuewqduigeqwudigequidequwduiqwgduiqwgduwqgduiwgqdgeqiuhequgeqiugfuieqdubeqd
        ueeyyeyuewyeuwyeuwuiegwqiuguieqwgiuewgyewfiewyfeqiygdiuewqduigeqwudigequidequwduiqwgduiqwgduwqgduiwgqdgeqiuhequgeqiugfuieqdubeqd
        ueeyyeyuewyeuwyeuwuiegwqiuguieqwgiuewgyewfiewyfeqiygdiuewqduigeqwudigequidequwduiqwgduiqwgduwqgduiwgqdgeqiuhequgeqiugfuieqdubeqd
        ueeyyeyuewyeuwyeuwuiegwqiuguieqwgiuewgyewfiewyfeqiygdiuewqduigeqwudigequidequwduiqwgduiqwgduwqgduiwgqdgeqiuhequgeqiugfuieqdubeqd
      </Text>


      <Switch
        value={activo}
        onValueChange={(valor) => setActivo(valor)}
      />

      <Text style={styles.titulo}>
        scrollview
      </Text>

      <ScrollView style={styles.lista}>
        <View style={[styles.tarjeta, {backgroundColor: 'red'}]}>
          <Text style={styles.textoTarjeta}>elemento 1 </Text>
        </View>
        <View style={[styles.tarjeta, {backgroundColor: 'blue'}]}>
          <Text style={styles.textoTarjeta}>elemento 2 </Text>
        </View>
        <View style={[styles.tarjeta, {backgroundColor: 'green'}]}>
          <Text style={styles.textoTarjeta}>elemento 3 </Text>
        </View>
        <View style={[styles.tarjeta, {backgroundColor: 'purple'}]}>
          <Text style={styles.textoTarjeta}>elemento 4 </Text>
        </View>
        <View style={[styles.tarjeta, {backgroundColor: 'pink'}]}>
          <Text style={styles.textoTarjeta}>elemento 5 </Text>
        </View>
        <View style={[styles.tarjeta, {backgroundColor: 'black'}]}>
          <Text style={styles.textoTarjeta}>elemento 6 </Text>
        </View>
        <View style={[styles.tarjeta, {backgroundColor: 'yellow'}]}>
          <Text style={styles.textoTarjeta}>elemento 7 </Text>
        </View>
      </ScrollView>

    </Contenedor>
  );
}


const styles = StyleSheet.create({
  fondo: {
    flex: 1,
    backgroundColor: '#1a1a2e',
    padding: 20,
  },
  titulo: {
    fontSize: 22,
    fontWeight: 'bold',
    color: '#ffffff',
    textAlign: 'center',
    marginBottom: 10,
  },
  descripcion: {
    fontSize: 13,
    color: '#aaaaaa',
    textAlign: 'center',
    marginBottom: 12,
  },
  fila: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    backgroundColor: 'rgba(255,255,255,0.08)',
    borderRadius: 12,
    padding: 12,
    marginBottom: 16,
  },
  etiqueta: {
    color: '#ffffff',
    fontSize: 14,
    marginBottom: 10,
  },
  lista: {
    flex: 1,
  },
  tarjeta: {
    height: 80,
    borderRadius: 12,
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 12,
  },
  textoTarjeta: {
    color: '#ffffff',
    fontSize: 16,
    fontWeight: 'bold',
  },
});