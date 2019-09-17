import React from 'react';
import Item from './Item';
import './Items.css';

const Items = ({ entries }) => {
    const { nbStudent, totalHour } = entries;
    const hour = 'Hour';
    const metaData = ['nbStudent', 'totalHour', 'avgHour', 'varianceHour', 'stdHour'];
    // pour pouvoir mapper les keys pour afficher les données
    const EntriesKeys = Object.keys(entries);

    return (
        <table className="table table-dark table-sm">
            <thead>
                <tr>
                    <th>Numéro</th>
                    <th>Name</th>
                    <th>Study time (hour)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td className="color">total :{nbStudent} students</td>
                    <td className="color">total :{totalHour} hours</td>
                </tr>
                {EntriesKeys.map(
                    (k, i) =>
                        metaData.includes(k) !== true ?
                            <Item key={i} student={k} time={entries[k]} number={i} /> : null
                )}
                <tr>
                    <td className="color">total :{nbStudent} students</td>
                    <td className="color">total :{totalHour} hours</td>
                </tr>
            </tbody>
        </table>
    );
}

export default Items;
