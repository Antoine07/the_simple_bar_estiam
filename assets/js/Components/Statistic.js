import React, { useState, useEffect } from 'react';

const Statistic = ({ data, dim }) => {

    const floor = (data) => Math.floor(data * 100) / 100;

    const average = (data) => {

        let dataLength = parseInt( data.length );

        return floor (data.reduce((acc, curr) => acc + parseInt(curr), 0) / dataLength);
    }
    const { n, size } = dim;
    const averageTotal = data.reduce((acc, lines) => average(lines) + acc, 0) / 10;

    // inverser les lignes en colonnes
    let column = [];
    data.forEach((lines, i) => {
        let newLine = [];
        data.forEach((l, j) => {
            newLine.push(l[i])
        });
        column.push(newLine);
    });

    const avgOfAvg = column.reduce((acc, lines) => average(lines) + acc, 0) / 10;

    return (
        <table class="table table-dark table-sm">
            <thead>
                <tr>
                    <th>#</th>
                    {
                        [...Array(size).keys()].map(
                            num => <th><span className="color">
                                {num + 1}
                            </span></th>
                        )
                    }
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colSpan={size}>La loi de distribution des valeurs est normale (random compris entre 0 et 20 équiprobale)</td>
                </tr>
                <tr>
                    <td colSpan={size}> Total des valeurs : {n}X{size} = {n * size} valeur(s) , valeurs comprises entres min : 0 et max : 20</td>
                </tr>
                <tr>
                    <td colSpan={size}>Moyenne générale {floor(averageTotal)}</td>
                </tr>
                <tr>
                    <td>#</td>
                    {
                        column.map(
                            line => {
                                let avg = average(line);

                                return (<td>{avg}</td>) }
                        )
                    }
                </tr>
                <tr>
                    <td colSpan={size}>Moyenne des moyennes {floor(avgOfAvg)}</td>
                </tr>
            </tbody>
        </table>
    )
};

export default Statistic;