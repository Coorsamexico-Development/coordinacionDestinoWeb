<script setup>
import { onMounted, onBeforeUpdate, ref, watch } from "vue";
import * as am4core from "@amcharts/amcharts4/core";
import * as am4charts from "@amcharts/amcharts4/charts";
import am4themes_animated from "@amcharts/amcharts4/themes/animated";
import ModalInfoViajes from '../Modals/ModalInfoViajes.vue'
import axios from 'axios';

var props = defineProps({
    data:Array,
    ubicaciones:Object,
    status_graph:Object,
    fecha:String
});

let chart = null;

watch(() => props.data,(nuevosValores) => 
    { //el whatcher observa el cambio de la data
        //console.log(nuevosValores);  //lo imprime
        chart.data = nuevosValores  
     });

am4core.useTheme(am4themes_animated);
onMounted(() => 
 {     
       // Themes begin
       am4core.useTheme(am4themes_animated);
       // Themes end
       
       // Create chart instance
       chart = am4core.create("chartdiv", am4charts.XYChart);
    
       // Add data
       chart.data = props.data
       /* 
       [{
         "ubicacion": "2016",
         'Liberada al 100':5,
         "europe": 10,
         "namerica": 2.5,
         "asia": 2.1,
         "lamerica": 0.3,
         "meast": 0.2,
         "africa": 0.1
       }, {
         "year": "2017",
         "europe": 2.6,
         "namerica": 2.7,
         "asia": 2.2,
         "lamerica": 0.3,
         "meast": 0.3,
         "africa": 0.1
       }, {
         "year": "2018",
         "europe": 2.8,
         "namerica": 2.9,
         "asia": 2.4,
         "lamerica": 0.3,
         "meast": 0.3,
         "africa": 0.1
       }];
       */
       // Create axes
       var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
       categoryAxis.dataFields.category = "ubicacion";
       categoryAxis.renderer.grid.template.location = 0;
       
       
       var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
       valueAxis.renderer.inside = true;
       valueAxis.renderer.labels.template.disabled = true;
       valueAxis.min = 0;
       
       // Create series
       function createSeries(field, name)
      {

         // Set up series
         var series = chart.series.push(new am4charts.ColumnSeries());
         series.name = name;
         series.dataFields.valueY = field;
         series.dataFields.categoryX = "ubicacion";
         series.sequencedInterpolation = true;
         //series.dataFields.categoryY = name;
        
         if(name == 'En riesgo')
         {
           series.columns.template.fill = am4core.color("#E86881"); // green fill
         }
         
         // Make it stacked
         series.stacked = true;
         
         // Configure columns
         series.columns.template.width = am4core.percent(60);
         series.columns.template.tooltipText = "[bold]{name}[/]\n[font-size:14px]{categoryX}: {valueY}";
         
         // Add label
         var labelBullet = series.bullets.push(new am4charts.LabelBullet());
         labelBullet.label.text = "{valueY}";
         labelBullet.locationY = 0.5;
         labelBullet.label.hideOversized = true;

         series.columns.template.events.on("hit", function(ev)
         {
            //console.log(ev.target.dataItem.component.dataFields.valueY);
            consultar(ev.target.dataItem.categoryX, ev.target.dataItem.component.dataFields.valueY)
         })
         
         return series;
       }
       
               
       for (let index = 0; index < props.status_graph.length; index++)
         {
            const statu = props.status_graph[index];

            createSeries(statu.nombre, statu.nombre);
            
         }

    /*  
       createSeries("europe", "Europe");
       createSeries("namerica", "North America");
       createSeries("asia", "Asia-Pacific");
       createSeries("lamerica", "Latin America");
       createSeries("meast", "Middle-East");
       createSeries("africa", "Africa");
    */
            
       chart.scrollbarX = new am4core.Scrollbar();
       chart.scrollbarX.parent = chart.bottomAxesContainer;
       // Legend
       chart.legend = new am4charts.Legend();

       
 });

 let viajes = ref([]);
 const consultar = async (ubicacion, status) => 
 {
   //console.log(ubicacion +' - ' + status)
    try 
    {
      await axios.get('/consultarConfirmaciones', {params:
      {
        fecha:props.fecha,
        ubicacion: ubicacion,
        status: status
        }}).then(response => 
        {
            //console.log(response.data)
            viajes.value = response.data;
            openModalInfo();

       }).catch(err => 
      {
        console.log(err)
      });
    } 
    catch (error) 
    {
      
    }
 }

 const modalInfo = ref(false);
 const openModalInfo = () => 
 {
    modalInfo.value = true;
 }

 const closeModalInfo = () =>
 {
   modalInfo.value = false;
 }
</script>
<template>
    <div id="chartdiv"></div>
    {{ viajes }}
    <div v-if="viajes.length > 0">
      <ModalInfoViajes :show="modalInfo" @close="closeModalInfo()" :viajes="viajes" />
    </div>
</template>
<style>
#chartdiv {
  width: 100%;
  height: 500px;
}
</style>