import React, { useState, useEffect } from 'react';
import './Estimate.css';

const min = 30;
const max = 100;

const Estimate = (props) => {

    const [sample, setSample] = useState(null);
    const [simulate, setSimulate] = useState(false);

    const [disable, setDisable] = useState(true);
    const [btn, setBtn] = useState(false);
    const [reload, setReload] = useState(false);

    const [messageError, setMessageError] = useState({
        sample: null
    });

    const handleSubmit = (event) => {
        event.preventDefault();
        setBtn(true);

        props.data({
            sample: sample,
            reload : reload,
            simulate : simulate
        });
    }

    useEffect(() => {
        validate(sample);
    }, [sample])

    const validate = (sample) => {

        if (sample < min || sample > max) setMessageError({
            sample: message(`La taille de l'échantilon doit être supérieure ou égale à ${min} et inférieur ou égale à ${max}`)
        })
        else {
            setMessageError({ sample: null })
        }

        if (sample >= min) setDisable(false);
        else {
            setDisable(true);
        }
    }

    const message = (txt) => <div className="alert alert-danger message" role="alert">
        {txt}
    </div>;

    const checkValue = (value) => {
        if(value != sample) setReload(true);
        else setReload(false);

        setSample(value);
    }
 
    return (
        <form className="form_stat" onSubmit={handleSubmit}>
            <div className="form-group row">
                <label>
                    Taille échantillon :
                    <input
                        required
                        placeholder={`min ${min} max : ${max}`}
                        className="form-control"
                        type="text" name="sample"
                        onChange={e => checkValue(e.target.value)}
                    />
                </label>
                {messageError.sample}
            </div>
            <div className="form-group row">
                <label>
                    Vous pouvez également lancer une simulation, elle se fera sur 1000 échantillons :
                    <input
                        className="form-control"
                        type="checkbox" name="simulate"
                        onChange={e => setSimulate(!simulate)}
                    />
                </label>
            </div>
            <button disabled={disable} type="submit" className="btn btn-primary">
                {btn? 'Reload' : 'Submit'}
            </button>
        </form>
    )
};

export default Estimate;