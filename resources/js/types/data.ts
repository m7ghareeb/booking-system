export type EmployeeData = {
id: number | null;
name: string | null;
slug: string | null;
profile_photo_url: string | null;
services: Array<ServiceData> | null;
};
export type ServiceData = {
id: number;
title: string;
slug: string;
duration: number;
price: string;
};
