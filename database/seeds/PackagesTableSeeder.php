<?php

use Illuminate\Database\Seeder;
use App\Models\Package;
use App\Models\PackageDetail;

class PackagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'G1',
                'products' => [
                    [
                        'package_id' => 1,
                        'product_id' => 1,
                        'qty' => 14
                    ]
                ]
            ],
            [
                'name' => 'G2',
                'products' => [
                    [
                        'package_id' => 2,
                        'product_id' => 2,
                        'qty' => 5
                    ]
                ]
            ],
            [
                'name' => 'G3',
                'products' => [
                    [
                        'package_id' => 3,
                        'product_id' => 3,
                        'qty' => 3
                    ]
                ]
            ],
            [
                'name' => 'G4',
                'products' => [
                    [
                        'package_id' => 4,
                        'product_id' => 4,
                        'qty' => 4
                    ]
                ]
            ],
            [
                'name' => 'G5',
                'products' => [
                    [
                        'package_id' => 5,
                        'product_id' => 1,
                        'qty' => 7
                    ],
                    [
                        'package_id' => 5,
                        'product_id' => 2,
                        'qty' => 1
                    ],
                    [
                        'package_id' => 5,
                        'product_id' => 3,
                        'qty' => 1
                    ]
                ]
            ],
            [
                'name' => 'G6',
                'products' => [
                    [
                        'package_id' => 6,
                        'product_id' => 1,
                        'qty' => 7
                    ],
                    [
                        'package_id' => 6,
                        'product_id' => 2,
                        'qty' => 1
                    ],
                    [
                        'package_id' => 6,
                        'product_id' => 4,
                        'qty' => 1
                    ]
                ]
            ],
            [
                'name' => 'G7',
                'products' => [
                    [
                        'package_id' => 7,
                        'product_id' => 1,
                        'qty' => 7
                    ],
                    [
                        'package_id' => 7,
                        'product_id' => 4,
                        'qty' => 1
                    ],
                    [
                        'package_id' => 7,
                        'product_id' => 7,
                        'qty' => 1
                    ]
                ]
            ],
            [
                'name' => 'G8',
                'products' => [
                    [
                        'package_id' => 8,
                        'product_id' => 1,
                        'qty' => 7
                    ],
                    [
                        'package_id' => 8,
                        'product_id' => 6,
                        'qty' => 1
                    ],
                    [
                        'package_id' => 8,
                        'product_id' => 7,
                        'qty' => 1
                    ]
                ]
            ],
            [
                'name' => 'G9',
                'products' => [
                    [
                        'package_id' => 9,
                        'product_id' => 2,
                        'qty' => 2
                    ],
                    [
                        'package_id' => 9,
                        'product_id' => 4,
                        'qty' => 1
                    ],
                    [
                        'package_id' => 9,
                        'product_id' => 5,
                        'qty' => 1
                    ]
                ]
            ],
            [
                'name' => 'G10',
                'products' => [
                    [
                        'package_id' => 10,
                        'product_id' => 2,
                        'qty' => 1
                    ],
                    [
                        'package_id' => 10,
                        'product_id' => 4,
                        'qty' => 1
                    ],
                    [
                        'package_id' => 10,
                        'product_id' => 6,
                        'qty' => 2
                    ]
                ]
            ],
            [
                'name' => 'G11',
                'products' => [
                    [
                        'package_id' => 11,
                        'product_id' => 1,
                        'qty' => 7
                    ],
                    [
                        'package_id' => 11,
                        'product_id' => 4,
                        'qty' => 1
                    ],
                    [
                        'package_id' => 11,
                        'product_id' => 7,
                        'qty' => 1
                    ]
                ]
            ],
            [
                'name' => 'G12',
                'products' => [
                    [
                        'package_id' => 12,
                        'product_id' => 4,
                        'qty' => 2
                    ],
                    [
                        'package_id' => 12,
                        'product_id' => 6,
                        'qty' => 1
                    ],
                    [
                        'package_id' => 12,
                        'product_id' => 7,
                        'qty' => 1
                    ]
                ]
            ],
            [
                'name' => 'G13',
                'products' => [
                    [
                        'package_id' => 13,
                        'product_id' => 2,
                        'qty' => 2
                    ],
                    [
                        'package_id' => 13,
                        'product_id' => 3,
                        'qty' => 1
                    ],
                    [
                        'package_id' => 13,
                        'product_id' => 4,
                        'qty' => 1
                    ]
                ]
            ],
            [
                'name' => 'G14',
                'products' => [
                    [
                        'package_id' => 14,
                        'product_id' => 17,
                        'qty' => 1
                    ],
                ]
            ],
            [
                'name' => 'G15',
                'products' => [
                    [
                        'package_id' => 15,
                        'product_id' => 18,
                        'qty' => 1
                    ]
                ]
            ],
            [
                'name' => 'G16',
                'products' => [
                    [
                        'package_id' => 16,
                        'product_id' => 19,
                        'qty' => 1
                    ]
                ]
            ],
        ];
        Package::truncate();
        PackageDetail::truncate();
        foreach ($data as $value) {
            Package::create([ 'name' => $value['name'] ]);
            foreach ($value['products'] as $product) {
                PackageDetail::create($product);
            }
        }
    }
}
