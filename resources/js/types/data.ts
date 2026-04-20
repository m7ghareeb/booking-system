export type EmployeeData = {
id: number;
name: string;
slug: string;
profile_photo_url: string;
services: Array<ServiceData>;
};
export type ServiceData = {
id: number;
title: string;
slug: string;
duration: number;
price: string;
};
