import React from 'react';

function HomePage({ apiData }) {
    return (
        <div className="container mx-auto">
            <h1 className="text-2xl font-medium">Yona CMS</h1>
            <p>Welcome!</p>

            {apiData &&
                <>
                    <div>Success: {apiData.success}</div>
                    <div>Env: {apiData.env}</div>
                </>
            }
        </div>
    )
}

export default HomePage