/*zona1: importaciones de componentes y archivos */
import { StatusBar } from 'expo-status-bar';
import { StyleSheet, Text, View, FlatList, SectionList, Button  } from 'react-native';
import { useState } from "react";

/*zona2: main - hogar de los componentes */
export default function FlatListScreen() {

    const [elementos, setElementos] = useState([
        {id:'1', nombre: 'Elemento 1'},
        {id:'2', nombre: 'Elemento 2'},
        {id:'3', nombre: 'Elemento 3'},
        {id:'4', nombre: 'Elemento 4'},
        {id:'5', nombre: 'Elemento 5'},
        {id:'6', nombre: 'Elemento 6'},
        {id:'7', nombre: 'Elemento 7'},
        {id:'8', nombre: 'Elemento 8'},
        {id:'9', nombre: 'Elemento 9'},
        {id:'10', nombre: 'Elemento 10'},
        {id:'11', nombre: 'Elemento 11'},
        {id:'12', nombre: 'Elemento 12'},
    ]);

    const [secciones, setSecciones] = useState([
      {
        tituloCategoria:'chescos',
        data:['coca','fanta','pepsi'],
      },
      {
        tituloCategoria:'papas',
        data:['sabritones','bolsas','chips'],
      },
      
      {
        tituloCategoria:'tacos',
        data:['barbacoa','pastor','suaperro'],
      },
            
    
      {
        tituloCategoria:'postres',
        data:['pay','gelatina','pan'],
      },

      
      {
        tituloCategoria:'cena',
        data:['alitas','bongles','hambuguesitas'],
      },
    ]);
    const eliminarElemento = (id) => {
      setElementos(elementos.filter(item => item.id != id))
    }

    const renderContenidoSuperior = () => (
      <View>
        <Text style={styles.titulo} >practica flatlist</Text>
        <FlatList
          data={elementos}
          keyExtractor={(item) => item.id}
          scrollEnabled={false}
          renderItem={({item}) => (
            <View style={styles.itemFlat}>
              <Text style={styles.texto}>{item.nombre}</Text>
              <Button title="Eliminar" onPress={() => eliminarElemento(item.id)}/>
            </View>
          )}
        />
        <View style={styles.barraDivisora}/>
        <Text style={styles.titulo}>practica seccion list</Text>
      </View>
    );
    
    return(
      <View style={styles.container}>
      <SectionList
      sections={secciones}
      keyExtractor={(item, index) => item + index}
      ListHeaderComponent={renderContenidoSuperior}
      renderItem={({item})=>(
        <View style={styles.itemSection}>
          <Text style={styles.texto}>{item}</Text>
        </View>
      )}
      renderSectionHeader={({section: {tituloCategoria}})=>(
        <View style={styles.encabezado}>
          <Text style={styles.textoEncabezado}>{tituloCategoria}</Text>
        </View>
      )}
      />
      </View>
    )
    
}
//Zona3: estilos - define los estilos para los componentes de la aplicación, en este caso, el contenedor principal
const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#ffffff',
    paddingTop: 60,
    paddingHorizontal: 20,
  },
  titulo: {
    fontSize: 20,
    fontWeight: 'bold',
    marginBottom: 20,
    textAlign: 'center',
  },
  itemFlat: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    padding: 10,
    borderBottomWidth: 1,
    borderBottomColor: '#cccccc',
  },
  itemSection: {
    padding: 12,
    borderBottomWidth: 1,
    borderBottomColor: '#b50000',
  },
  encabezado: {
    backgroundColor: '#ff0202',
    padding: 8,
    marginTop: 15,
  },
  textoEncabezado: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#333333',
  },
  texto: {
    fontSize: 16,
  },
  barraDivisora: {
    height: 2,
    backgroundColor: '#444444',
    marginVertical: 30,
    borderRadius: 1,
  },
});