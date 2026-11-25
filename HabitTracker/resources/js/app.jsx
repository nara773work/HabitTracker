import React from 'react';
import { createRoot } from 'react-dom/client';
import TreeGrowth from './TreeGrowth.jsx';

document.querySelectorAll('[data-tree-growth]').forEach((element) => {
    createRoot(element).render(
    <TreeGrowth
        ringCount={element.dataset.ringCount}
        targetDays={element.dataset.targetDays}
        completedToday={element.dataset.completedToday === '1'}
        mstatus={element.dataset.status}
        recoveryCount={Number(element.dataset.recoveryCount)}
        missedDays={Number(element.dataset.missedDays)}
    />
);
});