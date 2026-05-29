import React, { useState, useEffect } from 'react';

/**
 * Countdown
 * Props:
 *   - fechaExpire: string ISO de la fecha del evento (ej: "2025-12-31")
 */
export default function Countdown({ fechaExpire }) {
    const [texto, setTexto]      = useState('Cargando...');
    const [expirado, setExpirado] = useState(false);

    useEffect(() => {
        if (!fechaExpire) return;
        const targetDate = new Date(fechaExpire.replace(/-/g, '/')).getTime();

        const interval = setInterval(() => {
            const diff = targetDate - Date.now();

            if (diff <= 0) {
                setTexto('Finalizado / En Curso');
                setExpirado(true);
                clearInterval(interval);
                return;
            }

            const dias    = Math.floor(diff / (1000 * 60 * 60 * 24));
            const horas   = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutos = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));

            if (dias > 0)       setTexto(`Faltan ${dias} d y ${horas} h`);
            else if (horas > 0) setTexto(`Faltan ${horas} h y ${minutos} m`);
            else                setTexto('Inicia en menos de 1 h');
        }, 1000);

        return () => clearInterval(interval);
    }, [fechaExpire]);

    return (
        <span className={`text-[10px] font-black uppercase tracking-wider ${
            expirado ? 'text-stone-400' : 'text-red-600'
        }`}>
            {texto}
        </span>
    );
}