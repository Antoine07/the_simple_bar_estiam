import React, { useState, useEffect } from 'react';
import ReactDOM from 'react-dom';
import 'bootstrap/dist/css/bootstrap.css';
import Items from './Components/Items';
import Estimate from './Components/Estimate';
import Diagram from './Components/Diagram';

const url_generate = `http://localhost:8000/generate/`;

const App = () => {
    // pour récupérer les names/heures dans mon state de nom component App
    const [entries, setEntries] = useState([]);

    // statistique ...
    const [statPop, setStatPop] = useState({});
    const [dataEstimate, setDataEstimate] = useState({
        variance: 0,
        n: 0,
        avg: 0,
        entries: [],
        count : 0
    });
    const [avgCumulate, setAvgCumulate] = useState({
        count : 0,
        avg : [],
        dataset : {}
    });

    // arrondir à deux décimales
    const floor = (data) => Math.floor(data * 10) / 10;

    const count = (data) => {
        const countSameValue = {};
        data.forEach(function(x) { countSameValue[x] = (countSameValue[x] || 0)+1; });

        return countSameValue;
    }

    // attention de bien mettre un second paramètre ... Todo à voir plus précisément
    // mount and update
    useEffect(() => {

        async function fetchData() {
            // Call fetch as usual
            const res = await fetch(url_generate);

            // ce qui arrive le res on le transforme en json => asynchrone
            // donc on attend avec await
            const json = await res.json();

            // stat ...
            const { avgHour, stdHour, varianceHour, nbStudent } = json;

            setStatPop({
                avg: json.avgHour,
                std: json.stdHour,
                variance: json.varianceHour,
                n: json.nbStudent,
                interval: [floor(avgHour - 1.96 / stdHour), floor(avgHour + 1.96 / stdHour)]  // 5%
            });

            // modifie le state entries => mise à jour du render
            setEntries(json);
        }

        // lancer la méthode aysnc fetch pour récupérer les données
        fetchData();
    }, []); // <-- we didn't pass a value. what do you think will happen?


    const updateEstimate = (data) => {
        let avg = 0;
        let std = 0;
        const sample = parseInt(data.sample);

        const Entries = Object.values(entries).slice(0, -5)

        // shuffle
        const sampleEntries = Entries
            .sort(() => Math.random() - 0.5)
            .slice(0, sample);

        // estimateur sans biais
        avg = floor(sampleEntries.reduce((acc, current) => acc + current, 0) / sample);

        sampleEntries.map(
            time => { std += (time - avg) ** 2 }
        );

        setDataEstimate({
            variance: floor(std / (sample - 1)),
            n: sample,
            avg: avg,
            entries: sampleEntries,
            count : data.count
        });

        let countSameValue = {};
        avgCumulate.count++;
        avgCumulate.avg.push(avg);
        avgCumulate.avg.forEach((x) => { countSameValue[x] = (countSameValue[x] || 0)+1; });
        avgCumulate.dataset = countSameValue;
        setAvgCumulate(avgCumulate);
    }

    const { interval } = statPop;

    const run = (data) => {
        if( data.simulate == true )
        {
            let count = 0;
            while (count < 1000) { updateEstimate(data); count++ }
        }else{
            updateEstimate(data);
        }
    }

    return (
        <div className="container">
            <div className="row">
                <div className="col-6">
                    <Items entries={entries} />
                </div>
                <div className="col-6">
                    <h1>Estimate</h1>
                    <ul class="list-group">
                        <li className="list-group-item">
                            <small>Taille de la population : {statPop.n}</small>
                            <br />
                            <small>Moyenne pop : {statPop.avg}</small>
                            <br />
                            <small>Variance pop : {statPop.variance}</small>
                        </li>
                        <li className="list-group-item">Estimation :
                            <br />
                            <small>Taille de l'échantillon : {dataEstimate.n} </small>
                            <br />
                            <small>Intevalle de confiance à 5% : {interval ? `${interval[0]} et ${interval[1]}` : null} </small>
                            <br />
                            <small>Moyenne de l'échantillon : {dataEstimate.avg} </small>
                            <br />
                            <small>Variance sans biais de l'échantillon : {dataEstimate.variance} </small>
                        </li>
                    </ul>
                    <Estimate data={(d) => run(d)} />
                    {avgCumulate.avg.length > 0 ?
                        <Diagram entries={avgCumulate} />
                        : null
                    }
                </div>
            </div>
        </div>
    );
}

ReactDOM.render(<App />, document.getElementById('root'));