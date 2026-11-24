import React from 'react';

export default function TreeGrowth({
    ringCount = 0,
    targetDays = 100,
    completedToday = false,
}) {
    const count = Math.max(0, Math.floor(Number(ringCount) || 0));
    const target = Math.max(1, Number(targetDays) || 1);

    const ringStep = 6;
    const firstRadius = 14;
    const outerRadius = firstRadius + count * ringStep;
    const padding = 6;
    const viewBoxSize = (outerRadius + padding) * 2;
    const center = viewBoxSize / 2;
    const size = Math.min(viewBoxSize * 1.22 + 70, 430);

    const digits = String(count).length;
    const numberFontSize = Math.max(12, 36 - (digits - 1) * 6);

    return (
        <div className="tree-growth">
            <svg
                className="tree-growth__svg"
                width={size}
                height={size}
                viewBox={`0 0 ${viewBoxSize} ${viewBoxSize}`}
                role="img"
                aria-label={`年輪${count}本、目標${target}日`}
            >
                <circle
                    cx={center}
                    cy={center}
                    r={outerRadius}
                    fill="#f3d2b7"
                    stroke="none"
                />

                {Array.from({ length: count }, (_, index) => {
    const ringNumber = index + 1;
    const isTodayRing = completedToday && ringNumber === count;
    const isTenMark = ringNumber % 10 === 0;
    const todayStrokeWidth = ringStep * 0.5;

    const stroke = isTodayRing
        ? '#5b3928'
        : isTenMark
            ? '#f7e4d4'
            : '#e6b995';

    const radius = isTodayRing
        ? outerRadius - todayStrokeWidth / 2
        : firstRadius + (ringNumber - 0.5) * ringStep;

    return (
        <circle
            key={ringNumber}
            cx={center}
            cy={center}
            r={radius}
            fill="none"
            stroke={stroke}
            strokeWidth={isTodayRing ? todayStrokeWidth : 1}
        />
    );
})}

                <circle
                    cx={center}
                    cy={center}
                    r="5"
                    fill="#5b3928"
                />
            </svg>

            <div className="tree-growth__label">
                <strong style={{ fontSize: `${numberFontSize}px` }}>
                    {count}
                </strong>
                <span>日達成</span>
            </div>
        </div>
    );
}