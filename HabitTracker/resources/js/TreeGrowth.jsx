import React from 'react';

export default function TreeGrowth({
    ringCount = 0,
    targetDays = 100,
    completedToday = false,
    missedDays = 0,
    status = 'ongoing',
    recoveryCount = 0,
}) {
    const count = Math.max(0, Math.floor(Number(ringCount) || 0));
    const target = Math.max(1, Number(targetDays) || 1);
    const missed = Math.max(0, Number(missedDays) || 0);
    const restoredCount = Math.max(0, Number(recoveryCount) || 0);

    const ringStep = 6;
    const firstRadius = 14;
    const outerRadius = firstRadius + count * ringStep;
    const padding = 6;
    const viewBoxSize = (outerRadius + padding) * 2;
    const center = viewBoxSize / 2;
    const size = Math.min(viewBoxSize * 1.22 + 70, 430);

    const digits = String(count).length;
    const numberFontSize = Math.max(12, 36 - (digits - 1) * 6);

    const isCompleted = status === 'completed';
    const grayWholeTree =
        !isCompleted && (status === 'withered' || missed >= 2);

    return (
        <div className={`tree-growth${grayWholeTree ? ' is-gray' : ''}`}>
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
                    fill={grayWholeTree ? '#c8ccc8' : '#f3d2b7'}
                    stroke="none"
                />

                {Array.from({ length: count }, (_, index) => {
                    const ringNumber = index + 1;
                    const isTodayRing =
                        completedToday && ringNumber === count;
                    const isTenMark = ringNumber % 10 === 0;

                    const isYesterdayRing =
                        !isCompleted &&
                        !grayWholeTree &&
                        missed === 1 &&
                        ringNumber === (completedToday ? count - 1 : count);

                    const isGrayRing =
                        grayWholeTree || isYesterdayRing;

                    const isBrownRing =
                        isCompleted ||
                        ringNumber <= restoredCount ||
                        isTodayRing;

                    const stroke = isGrayRing
                        ? '#a5aaa5'
                        : isBrownRing
                            ? '#79543c'
                            : isTenMark
                                ? '#e6cbb5'
                                : '#d9ad8c';

                    const strokeWidth = isGrayRing
                        ? ringStep * 0.35
                        : isBrownRing
                            ? ringStep * 0.45
                            : 1;

                    const radius = isTodayRing
                        ? outerRadius - strokeWidth / 2
                        : firstRadius + (ringNumber - 0.5) * ringStep;

                    return (
                        <circle
                            key={ringNumber}
                            cx={center}
                            cy={center}
                            r={radius}
                            fill="none"
                            stroke={stroke}
                            strokeWidth={strokeWidth}
                        />
                    );
                })}

                <circle
                    cx={center}
                    cy={center}
                    r="5"
                    fill={grayWholeTree ? '#858c85' : '#79543c'}
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