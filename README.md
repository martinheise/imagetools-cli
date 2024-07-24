# Demo Image Tools per CLI

This is a small setup to demonstrate and test the usage of [mhe/imagetools](https://github.com/martinheise/imagetools) module.

```
php resize-image.php -i <inputfile> -s <"sizes"> -m <mode>
```

e.g.
```
php resize-image.php -i testimage.jpg -s "(max-width: 700px) 100vw, 680px" -m IM
```

The two classes `GDImageData` and `IMImageData` show a very basic implementation of the `ImageData` interface using local files, without any caching etc.

The generated image renditions are written to subfolder `_resample`.

This is a just quick and dirty CLI script – **Not meant for productive use.**
