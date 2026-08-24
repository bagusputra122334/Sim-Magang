<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Baris bahasa berikut berisi pesan error default yang digunakan oleh
    | kelas validator. Beberapa aturan memiliki beberapa versi seperti
    | aturan ukuran. Silakan sesuaikan pesan-pesan di bawah ini.
    |
    */

    'accepted'               => ':attribute harus diterima.',
    'accepted_if'            => ':attribute harus diterima ketika :other adalah :value.',
    'active_url'             => ':attribute bukan URL yang valid.',
    'after'                  => ':attribute harus berupa tanggal setelah :date.',
    'after_or_equal'         => ':attribute harus berupa tanggal setelah atau sama dengan :date.',
    'alpha'                  => ':attribute hanya boleh berisi huruf.',
    'alpha_dash'             => ':attribute hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
    'alpha_num'              => ':attribute hanya boleh berisi huruf dan angka.',
    'any_of'                 => ':attribute tidak valid.',
    'array'                  => ':attribute harus berupa larik (array).',
    'array_keys'             => ':attribute hanya boleh berisi kunci berikut: :values.',
    'ascii'                  => ':attribute hanya boleh berisi karakter alfanumerik dan simbol satu-byte.',
    'base64'                 => ':attribute harus berupa string Base64 yang valid.',
    'before'                 => ':attribute harus berupa tanggal sebelum :date.',
    'before_or_equal'        => ':attribute harus berupa tanggal sebelum atau sama dengan :date.',
    'between'                => [
        'array'   => ':attribute harus memiliki antara :min sampai :max item.',
        'file'    => ':attribute harus berukuran antara :min sampai :max kilobita.',
        'numeric' => ':attribute harus bernilai antara :min sampai :max.',
        'string'  => ':attribute harus memiliki antara :min sampai :max karakter.',
    ],
    'boolean'                => ':attribute harus bernilai benar atau salah.',
    'can'                    => ':attribute mengandung nilai yang tidak diizinkan.',
    'confirmed'              => 'Konfirmasi :attribute tidak cocok.',
    'contains'               => ':attribute tidak memiliki nilai yang diperlukan.',
    'current_password'       => 'Kata sandi saat ini tidak sesuai.',
    'date'                   => ':attribute bukan tanggal yang valid.',
    'date_equals'            => ':attribute harus berupa tanggal yang sama dengan :date.',
    'date_format'            => ':attribute tidak sesuai dengan format :format.',
    'decimal'                => ':attribute harus memiliki :decimal tempat desimal.',
    'declined'               => ':attribute harus ditolak.',
    'declined_if'            => ':attribute harus ditolak ketika :other adalah :value.',
    'different'              => ':attribute dan :other harus berbeda.',
    'digits'                 => ':attribute harus sebanyak :digits digit.',
    'digits_between'         => ':attribute harus memiliki antara :min sampai :max digit.',
    'distinct'               => ':attribute memiliki nilai yang duplikat.',
    'doesnt_end_with'        => ':attribute tidak boleh diakhiri dengan salah satu dari: :values.',
    'doesnt_start_with'      => ':attribute tidak boleh diawali dengan salah satu dari: :values.',
    'email'                  => ':attribute harus berupa alamat email yang valid.',
    'ends_with'              => ':attribute harus diakhiri dengan salah satu dari: :values.',
    'enum'                   => 'Pilihan :attribute tidak valid.',
    'exists'                 => ':attribute yang dipilih tidak valid.',
    'extensions'             => ':attribute harus memiliki ekstensi file salah satu dari: :values.',
    'file'                   => ':attribute harus berupa file.',
    'filled'                 => ':attribute harus memiliki nilai.',
    'gt'                     => [
        'array'   => ':attribute harus memiliki lebih dari :value item.',
        'file'    => ':attribute harus lebih besar dari :value kilobita.',
        'numeric' => ':attribute harus lebih besar dari :value.',
        'string'  => ':attribute harus lebih dari :value karakter.',
    ],
    'gte'                    => [
        'array'   => ':attribute harus memiliki :value item atau lebih.',
        'file'    => ':attribute harus lebih besar atau sama dengan :value kilobita.',
        'numeric' => ':attribute harus lebih besar atau sama dengan :value.',
        'string'  => ':attribute harus lebih dari atau sama dengan :value karakter.',
    ],
    'hex_color'              => ':attribute harus berupa kode warna heksadesimal yang valid.',
    'image'                  => ':attribute harus berupa gambar.',
    'in'                     => ':attribute yang dipilih tidak valid.',
    'in_array'               => ':attribute tidak ada di dalam :other.',
    'integer'                => ':attribute harus berupa bilangan bulat (integer).',
    'ip'                     => ':attribute harus berupa alamat IP yang valid.',
    'ipv4'                   => ':attribute harus berupa alamat IPv4 yang valid.',
    'ipv6'                   => ':attribute harus berupa alamat IPv6 yang valid.',
    'json'                   => ':attribute harus berupa string JSON yang valid.',
    'list'                   => ':attribute harus berupa daftar.',
    'lowercase'              => ':attribute harus berupa huruf kecil.',
    'lt'                     => [
        'array'   => ':attribute harus memiliki kurang dari :value item.',
        'file'    => ':attribute harus berukuran kurang dari :value kilobita.',
        'numeric' => ':attribute harus kurang dari :value.',
        'string'  => ':attribute harus kurang dari :value karakter.',
    ],
    'lte'                    => [
        'array'   => ':attribute tidak boleh memiliki lebih dari :value item.',
        'file'    => ':attribute harus berukuran kurang dari atau sama dengan :value kilobita.',
        'numeric' => ':attribute harus kurang dari atau sama dengan :value.',
        'string'  => ':attribute harus kurang dari atau sama dengan :value karakter.',
    ],
    'mac_address'            => ':attribute harus berupa alamat MAC yang valid.',
    'max'                    => [
        'array'   => ':attribute tidak boleh memiliki lebih dari :max item.',
        'file'    => ':attribute tidak boleh lebih besar dari :max kilobita.',
        'numeric' => ':attribute tidak boleh lebih besar dari :max.',
        'string'  => ':attribute tidak boleh lebih dari :max karakter.',
    ],
    'max_digits'             => ':attribute tidak boleh memiliki lebih dari :max digit.',
    'mimes'                  => ':attribute harus berupa file dengan tipe: :values.',
    'mimetypes'              => ':attribute harus berupa file dengan tipe: :values.',
    'min'                    => [
        'array'   => ':attribute harus memiliki setidaknya :min item.',
        'file'    => ':attribute harus berukuran setidaknya :min kilobita.',
        'numeric' => ':attribute harus minimal :min.',
        'string'  => ':attribute harus minimal :min karakter.',
    ],
    'min_digits'             => ':attribute harus memiliki setidaknya :min digit.',
    'missing'                => ':attribute harus tidak ada.',
    'missing_if'             => ':attribute harus tidak ada ketika :other adalah :value.',
    'missing_unless'         => ':attribute harus tidak ada kecuali :other ada di dalam :values.',
    'missing_with'           => ':attribute harus tidak ada ketika :values ada.',
    'missing_with_all'       => ':attribute harus tidak ada ketika :values semuanya ada.',
    'multiple_of'            => ':attribute harus kelipatan dari :value.',
    'not_in'                 => ':attribute yang dipilih tidak valid.',
    'not_regex'              => 'Format :attribute tidak valid.',
    'numeric'                => ':attribute harus berupa angka.',
    'password'               => [
        'letters'       => ':attribute harus mengandung setidaknya satu huruf.',
        'mixed'         => ':attribute harus mengandung setidaknya satu huruf kapital dan satu huruf kecil.',
        'numbers'       => ':attribute harus mengandung setidaknya satu angka.',
        'symbols'       => ':attribute harus mengandung setidaknya satu simbol.',
        'uncompromised' => ':attribute yang diberikan telah muncul dalam kebocoran data. Silakan pilih :attribute yang lain.',
    ],
    'present'                => ':attribute harus tersedia.',
    'present_if'             => ':attribute harus tersedia ketika :other adalah :value.',
    'present_unless'         => ':attribute harus tersedia kecuali :other ada di dalam :values.',
    'present_with'           => ':attribute harus tersedia ketika :values ada.',
    'present_with_all'       => ':attribute harus tersedia ketika :values semuanya ada.',
    'prohibited'             => ':attribute dilarang diisi.',
    'prohibited_if'          => ':attribute dilarang diisi ketika :other adalah :value.',
    'prohibited_if_accepted' => ':attribute dilarang diisi ketika :other diterima.',
    'prohibited_if_declined' => ':attribute dilarang diisi ketika :other ditolak.',
    'prohibited_unless'      => ':attribute dilarang kecuali :other ada di dalam :values.',
    'prohibits'              => ':attribute melarang :other untuk ada.',
    'regex'                  => 'Format :attribute tidak valid.',
    'required'               => ':attribute wajib diisi.',
    'required_array_keys'    => ':attribute harus berisi entri untuk: :values.',
    'required_if'            => ':attribute wajib diisi ketika :other adalah :value.',
    'required_if_accepted'   => ':attribute wajib diisi ketika :other diterima.',
    'required_if_declined'   => ':attribute wajib diisi ketika :other ditolak.',
    'required_unless'        => ':attribute wajib diisi kecuali :other ada di dalam :values.',
    'required_with'          => ':attribute wajib diisi ketika :values ada.',
    'required_with_all'      => ':attribute wajib diisi ketika :values semuanya ada.',
    'required_without'       => ':attribute wajib diisi ketika :values tidak ada.',
    'required_without_all'   => ':attribute wajib diisi ketika tidak ada satu pun dari :values yang ada.',
    'same'                   => ':attribute dan :other harus sama.',
    'size'                   => [
        'array'   => ':attribute harus mengandung :size item.',
        'file'    => ':attribute harus berukuran :size kilobita.',
        'numeric' => ':attribute harus :size.',
        'string'  => ':attribute harus :size karakter.',
    ],
    'starts_with'            => ':attribute harus diawali dengan salah satu dari: :values.',
    'string'                 => ':attribute harus berupa teks (string).',
    'timezone'               => ':attribute harus berupa zona waktu yang valid.',
    'unique'                 => ':attribute sudah digunakan.',
    'uploaded'               => ':attribute gagal diunggah.',
    'uppercase'              => ':attribute harus berupa huruf kapital.',
    'url'                    => ':attribute harus berupa URL yang valid.',
    'ulid'                   => ':attribute harus berupa ULID yang valid.',
    'uuid'                   => ':attribute harus berupa UUID yang valid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Di sini Anda dapat menentukan pesan validasi kustom menggunakan
    | konvensi "attribute.rule". Ini membuat cepat untuk menentukan
    | baris bahasa kustom untuk aturan atribut tertentu.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'pesan-kustom',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | Baris bahasa berikut digunakan untuk menukar placeholder atribut
    | dengan teks yang lebih mudah dibaca seperti "Alamat Email" daripada
    | "email". Ini membantu membuat pesan lebih ekspresif.
    |
    */

    'attributes' => [
        'name'                  => 'Nama',
        'email'                 => 'Alamat Email',
        'password'              => 'Kata Sandi',
        'password_confirmation' => 'Konfirmasi Kata Sandi',
        'remember'              => 'Ingat Saya',
    ],

];
