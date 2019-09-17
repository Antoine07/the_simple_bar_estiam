import React, { useRef, useEffect, useState } from 'react';
import Chart from "chart.js";

const Diagram = ({ entries }) => {

    const [count, setCount] = useState(0);

    const chartRef = useRef(null);

    useEffect(() => {
        console.log(entries);

        setCount(entries.count);

        const Entries = Object.keys(entries.dataset).sort();
        console.log(Entries);

        const datasetX = [];
        const datasetY = [];

        Entries.forEach(key => {
            datasetX.push(key);
            datasetY.push(entries.dataset[key]);
        });


        const myChartRef = chartRef.current.getContext("2d");
        new Chart(myChartRef, {
            type: 'bar',
            data: {
                labels: datasetX,
                datasets: [{
                    label: `Répartition de la moyenne des moyennes, nb expérience : ${count}`,
                    data: datasetY,
                    backgroundColor: 'rgba(255, 99, 132, 1)',
                }]
            },
            options: {
              
            }
        });

    });

    return (
        <div >
            <canvas
                id="myChart"
                ref={chartRef}
            />
        </div>
    )
};

export default Diagram;