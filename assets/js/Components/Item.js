import React from 'react';

const Item = ({ student, time, number }) => (
   <tr>
      <td><small>{number + 1}</small></td><td><small>{student}</small></td><td><small>{time}h</small></td>
   </tr>
);

export default Item;