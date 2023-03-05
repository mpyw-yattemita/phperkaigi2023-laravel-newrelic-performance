"use strict";

const baseUrl = process.env.K8S_LARAVEL_APP_URL;

const qs = (obj) =>
    Object.entries(obj)
        .map(([k, v]) => encodeURIComponent(k) + "=" + encodeURIComponent(v))
        .join("&");

const senders = {
    view: (params) =>
        fetch(`${baseUrl}/api/view?${qs(params)}`, {
            method: "GET",
        }),
    update: (params, json) =>
        fetch(`${baseUrl}/api/update?${qs(params)}`, {
            method: "POST",
            body: JSON.stringify(json),
            headers: {
                "Content-Type": "application/json",
            },
        }),
    validate: (params, json) =>
        fetch(`${baseUrl}/api/validate?${qs(params)}`, {
            method: "POST",
            body: JSON.stringify(json),
            headers: {
                "Content-Type": "application/json",
            },
        }),
};

const main = async () => {
    const responses = {
        withoutCities: { data: [] },
        withoutDistricts: { data: [] },
        full: { data: [] },
    };

    {
        const names = [
            "with_lazy_children_cities",
            "with_lazy_children_districts",
            "with_eager_children_cities",
            "with_eager_children_districts",
            "with_eager_parent",
        ];
        const values = [
            { params: [0, 0, 0, 0, 0], responseName: "withoutCities" },
            { params: [1, 0, 0, 0, 0], responseName: null },
            { params: [1, 1, 0, 0, 0], responseName: null },
            { params: [0, 0, 1, 0, 0], responseName: null },
            { params: [0, 0, 1, 0, 1], responseName: "withoutDistricts" },
            { params: [0, 0, 1, 1, 0], responseName: null },
            { params: [0, 0, 1, 1, 1], responseName: "full" },
        ];
        const patterns = values.map(({ params, responseName }) => {
            return {
                responseName,
                params: Object.assign(
                    {},
                    ...names.map((name, i) => {
                        return params[i] ? { [name]: params[i] } : {};
                    })
                ),
            };
        });
        for (const { responseName, params } of patterns) {
            console.log("Sending: ", { action: "view", params });
            const response = await senders.view(params).then((r) => r.json());
            console.log("Received: ", { action: "view", params, response });
            if (responseName) {
                responses[responseName] = response;
            }
        }
    }

    {
        const names = [
            "with_laravel_validation",
            "with_wildcard_less_laravel_validation",
            "with_pure_php_validation",
        ];
        const values = [
            [0, 0, 0],
            [1, 0, 0],
            [0, 1, 0],
            [0, 0, 1],
        ];
        const patterns = values.map((fields) => {
            return Object.assign(
                {},
                ...names.map((name, i) => {
                    return fields[i] ? { [name]: fields[i] } : {};
                })
            );
        });
        for (const action of ["update", "validate"]) {
            for (const jsonName of [
                "withoutCities",
                "withoutDistricts",
                "full",
            ]) {
                for (const params of patterns) {
                    console.log("Sending: ", {
                        action,
                        params,
                        jsonName,
                    });
                    await senders[action](params, responses[jsonName]);
                    console.log("Received: ", {
                        action,
                        params,
                        jsonName,
                    });
                }
            }
        }
    }
};

main().catch(console.error);
