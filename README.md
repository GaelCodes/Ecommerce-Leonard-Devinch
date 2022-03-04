# <p align="center"> - Ecommerce-Leonard-Devinch - </p>
##### <p align="center"> _Este documento forma parte de la documentación procedimental del proyecto_ </p>
## <p align="center"> Contexto </p>

Al Sr. Leonard Devinch le ha gustado la solución que le hemos ofrecido al reconocido artista Messier Joan Dupont. Tiene pensado contratar nuestros servicios para llevar a cabo una idea que le ronda por la cabeza. Aunque es parecida a la solución que le proporcionamos a Messier Dupont, el Sr. Devinch quiere bastantes mejoras.

## <p align="center"> Análisis </p>
### <p align="center"> Técnica de Análisis 1 - Entrevista con el cliente </p>

En la entrevista que se mantuvo con el Sr. Devinch se enunciaron los siguientes puntos claves de la solución de software:

En primer lugar, quiere que su sitio web sea un ecommerce en que pintores reconocidos puedan vender sus obras. Quiere que la web tenga una estética más acorde al contenido, menciona que la Gallery-Joan-Dupont es muy "sosa".

En segundo lugar, dice que solo los usuarios registrados podrán comprar en el ecommerce, sin embargo todos podrán ver las obras. Los usarios deberán tener un menú en el cual puedan filtrar las obras por título, nombre del artista, fecha en la que fue terminada, precio, tamaño y título. Dado que habrá mucho tráfico sensible de por medio solicita que todo funcione bajo protocolos seguros. Además, habrá que vigilar el rendimiento puesto que el tiempo del cliente es lo más importante.

El producto que se venderá en el ecommerce serán obras creadas por ilustres artistas. Requiere almacenar la siguiente información relativa a los artistas; fecha de nacimiento,nombre completo,correo,cantidad de obras creadas y estilo. De las obras creadas por los artistas necesitará saber lo siguiente; título, temáticas, fecha de inicio, fecha de finalización, cantidad disponible, cantidad creada, dimensión en el eje X, dimensión en el eje Y y el precio actual de la obra. En cuanto a los clientes del ecommerce, se deberán conocer estos datos; correo, dirección de envío, nombre completo y el número de teléfono.  También quieren poder recuperar la siguiente información de las compras efectuadas por los clientes; importe total de la compra, qué artículos(obras) se han comprado, el precio de las obras en el momento de la compra, la fecha de realización de la compra y la cantidad total de artículos de la compra.



### <p align="center"> Técnica de Análisis 2 - Brainstorming </p>

En una sesión con todos los integrantes del equipo de desarrollo se realizó una tormenta de ideas para determinar las características del proyecto.
Cada punto de la sesión se divide en 2 partes, los requisitos que la solución debe cumplir y las soluciones propuestas por los desarrolladores para cubrir cada requisito.

- Debe funcionar sobre protocolos seguros:
  - soluciones propuestas

    - Developer1: " Desarrollemoslo en firebase, provee certificados https automáticos, gratuitos y con implementación automática. "

    - DeveloperX: " Firebase no cuenta con BBDDs relacionales, tampoco hay nadie en el equipo que domine node para el backend, si usamos 
    firebase para el frontend y desplegamos el backend en otro servidor/alojamiento que ejecute PHP y además tenga BBDDs relacionales podremos cubrir todos los requisitos aplicandole HTTPS tanto a las comunicaciones del frontend con el backend como a la interacción de los usuarios con el frontend. "

- Debe tener autenticación de usuarios:
  - soluciones propuestas
    
    - Developer1: " Podríamos usar el típico control de usuarios mediante sesiones, formularios de login y validación de los datos introducidos por los usuarios. "

    - Developer2: " Habría que tener en cuenta que gestionar el estado de un usuario usando sesiones supondría una carga extra para el servidor, por mínima que fuera. Desde mi punto de vista, podríamos dar un mejor uso a los recursos del servidor de backend si dejamos que sea el frontend que almacene toda la información posible. De tal forma, la concurrencia masiva de usuarios no amenazaría nuestra alta disponibilidad. Por lo tanto, propongo que optemos por un método de autenticación basado en tokens o cookies, concretamente JWT (Json Web Token). "

    - Developer1: " Tienes razón es un planteamiento mucho mejor, hagamoslo así. "

- Debe tener un rendimiento óptimo:
  - soluciones propuestas

    - Developer1: " Siguiendo por este punto, sería considerable plantearse un sistema con una arquitectura basada en el modelo API REST para las comunicaciones cliente-servidor. Esto nos permitiría cubrir aspectos importantes en el producto como pueden ser; la escalabilidad, la independencia en el proceso de desarrollo, independencia en las tecnologías de backend y frontend además de que supondría una mejora en el rendimiento general de la aplicación, puesto que en cada petición no se transmiten más datos de los necesarios (uso optimizado del ancho de bando). "

    - Developer3: " Yo lo veo. "


Tras la aplicación de las sucesivas técnicas de análisis se han concretado los requisitos funcionales y no funcionales del software.
Estos vienen adecuadamente recogidos en el Documento de Especificación de Requisitos del Software, [ERS.pdf](documentación\documentación%20del%20producto\documentación%20del%20sistema\documentación%20análisis\ERS.pdf).



## <p align="center"> Diseño </p>


### <p align="left"> Diseño de la topología de Red del sistema </p>
Para realizar el diseño de la topología de Red del sistema se ha tenido en cuenta los datos recopilados durante el análisis. Esto ha resultado en el [Diagrama de Esquema de red.pdf](documentación\documentación%20del%20producto\documentación%20del%20sistema\documentación%20del%20diseño\Diagrama%20de%20esquema%20de%20red.pdf)


### <p align="left"> Diseño de la BBDD </p>
Para realizar el diseño de la BBDD se ha tenido en cuenta los datos recopilados durante el análisis. Esto ha resultado en el [Diagrama entidad-relación.pdf](documentación\documentación%20del%20producto\documentación%20del%20sistema\documentación%20del%20diseño\diagrama%20de%20entidad-realación.pdf) y en su respectivo [Diagrama del Modelo relacional.pdf](documentación\documentación%20del%20producto\documentación%20del%20sistema\documentación%20del%20diseño\diagrama%20modelo%20relacional.pdf).


### <p align="left"> Diseño del código Backend </p>
Para realizar el diseño del código del Backend se ha tenido en cuenta los datos recopilados durante el análisis y el [Diagrama de secuencia - proceso de compra.pdf](documentación\documentación%20del%20producto\documentación%20del%20sistema\documentación%20del%20diseño\diagrama%20de%20secuencia%20-%20proceso%20de%20compra.pdf) proporcionados por los ingenieros. Esto ha resultado en el [Diagrama de clases del backend.pdf](documentación\documentación%20del%20producto\documentación%20del%20sistema\documentación%20del%20diseño\diagrama%20de%20clases%20del%20backend.pdf).


### <p align="left"> Diseño del código Frontend </p>
Para realizar el diseño del código del Frontend se ha tenido en cuenta los datos recopilados durante el análisis y los diagramas de casos de uso; [Diagramas de casos de uso - usuarios autentificados.pdf](documentación\documentación%20del%20producto\documentación%20del%20sistema\documentación%20del%20diseño\diagrama%20de%20casos%20de%20uso%20-%20usuarios%20autentificados.pdf) y [Diagramas de casos de uso - usuarios no autentificados.pdf](documentación\documentación%20del%20producto\documentación%20del%20sistema\documentación%20del%20diseño\diagrama%20de%20casos%20de%20uso%20-%20usuarios%20no%20autentificados.pdf) proporcionados por los ingenieros. También se ha adjuntado un [Diagrama de flujo - Carga de Páginas.pdf](documentación\documentación%20del%20producto\documentación%20del%20sistema\documentación%20del%20diseño\Diagrama%20de%20flujo%20-%20Carga%20de%20Páginas.pdf) que describe el proceso que cargan todas las páginas cada vez que un usuario las solicita, en él se describen los pasos que optimizan la carga de la UI y la comprobación de autorización de un usuario para navegar a través de las distintas partes de la aplicación. Esto ha resultado en el [Diagrama de clases del frontend.pdf](documentación\documentación%20del%20producto\documentación%20del%20sistema\documentación%20del%20diseño\diagrama%20de%20clases%20del%20frontend.pdf), en el cual se implementa el patrón de diseño MVC en varios componentes, como pueden ser las Artworks o los ShoppingCartItems.


#### <p align="left"> Diseño UI </p>

Para el desarrollo de la UI se han tenido en cuenta los [diseños proporcionados por el diseñador](documentación\documentación%20del%20producto\documentación%20del%20sistema\documentación%20del%20diseño\diseño%20UI). También se han obtenido diseños sencillos de las facturas de los pedidos ubicadas en el mismo directorio.

## <p align="center"> Código </p>

Toda la documentación relativa al código se encuentra adjunta al mismo en forma de comentarios.

## <p align="center"> Pruebas </p>

Para este proyecto no se han realizado pruebas automáticas.

## <p align="center"> Documentación </p>

La documentación de este proyecto tiene la siguiente estructura:

- [documentación del producto](documentación\documentación%20del%20producto)
    - [documentación del sistema](documentación\documentación%20del%20producto\documentación%20del%20sistema)
        - [documentación del análisis](documentación\documentación%20del%20producto\documentación%20del%20sistema\documentación%20del%20análisis)
            - [ERS.pdf](documentación\documentación%20del%20producto\documentación%20del%20sistema\documentación%20análisis\ERS.pdf)
        - [documentación del diseño](documentación\documentación%20del%20producto\documentación%20del%20sistema\documentación%20del%20diseño)

          - [Diagrama de casos de uso - usuarios autentificados.pdf](documentación\documentación%20del%20producto\documentación%20del%20sistema\documentación%20del%20diseño\diagrama%20de%20casos%20de%20uso%20-%20usuarios%20autentificados.pdf)
          - [Diagrama de casos de uso - usuarios no autentificados.pdf](documentación\documentación%20del%20producto\documentación%20del%20sistema\documentación%20del%20diseño\diagrama%20de%20casos%20de%20uso%20-%20usuarios%20no%20autentificados.pdf)
          - [Diagrama de clases del backend.pdf](documentación\documentación%20del%20producto\documentación%20del%20sistema\documentación%20del%20diseño\diagrama%20de%20clases%20del%20backend.pdf)
          - [Diagrama de clases del frontend.pdf](documentación\documentación%20del%20producto\documentación%20del%20sistema\documentación%20del%20diseño\diagrama%20de%20clases%20del%20frontend.pdf)
          - [Diagrama de Esquema de red.pdf](documentación\documentación%20del%20producto\documentación%20del%20sistema\documentación%20del%20diseño\Diagrama%20de%20esquema%20de%20red.pdf)
          - [Diagrama de flujo - Carga de Páginas.pdf](documentación\documentación%20del%20producto\documentación%20del%20sistema\documentación%20del%20diseño\Diagrama%20de%20flujo%20-%20Carga%20de%20Páginas.pdf)
          - [Diagrama de secuencia - proceso de compra.pdf](documentación\documentación%20del%20producto\documentación%20del%20sistema\documentación%20del%20diseño\diagrama%20de%20secuencia%20-%20proceso%20de%20compra.pdf)
          - [Diagrama entidad-relación.pdf](documentación\documentación%20del%20producto\documentación%20del%20sistema\documentación%20del%20diseño\diagrama%20de%20entidad-realación.pdf)
          - [Diagrama del Modelo relacional.pdf](documentación\documentación%20del%20producto\documentación%20del%20sistema\documentación%20del%20diseño\diagrama%20modelo%20relacional.pdf)
          
        - [documentación del código](documentación\documentación%20del%20producto\documentación%20del%20sistema\documentación%20del%20código)
        
        - [documentación de las pruebas](documentación\documentación%20del%20producto\documentación%20del%20sistema\documentación%20de%20las%20pruebas)

        - [Glosario.pdf](documentación\documentación%20del%20producto\documentación%20del%20sistema\Glosario.pdf)

    - [documentación del usuario](documentación\documentación%20del%20producto\documentación%20del%20usuario)
        - [Manual Introductorio.pdf](documentación\documentación%20del%20producto\documentación%20del%20usuario\Manual%20Introductorio.pdf)

        - [Manual de Referencia.pdf](documentación\documentación%20del%20producto\documentación%20del%20usuario\Manual%20de%20Referencia.pdf)

        - [Guía del Administrador.pdf](documentación\documentación%20del%20producto\documentación%20del%20usuario\Guía%20del%20Administrador.pdf)

- [documentación procedimental](documentación\documentación%20procedimental)
    - [README.md](documentación\documentación%20procedimental\README.md)