
# KeyVal Parser is a plain text parser to php variables

## Usage, consider the following text

    title:: The Title
    ----
    intro:: Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. **Ut enim ad minim veniam**,
    ----
    helpUrl:: http://google.com

    [[bucket]]
    title:: Create Your Own Reports
    ----
    body:: Click on the links below to generate your own reports based on competitive sets and date ranges of your choosing.
    ----
    linkUrl:: http://google.com
    ----
    linkLabel:: Link
    [[bucket/]]

    [[bucket]]
    title:: Lorem ipsum dolor sit amet.
    ----
    body:: Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
    ----
    linkUrl:: http://google.com
    ----
    linkLabel:: Hey Alex
    [[bucket/]]

    [[dashboardLink]]
    linkUrl:: http://google.com
    ----
    linkLabel:: Link
    [[dashboardLink/]]

    [[dashboardLink]]
    linkHref:: http://google.com
    ----
    linkLabel:: Link
    [[dashboardLink/]]


## When parsed like so

    $parser =  new PostParser( $text );
    print_r( $parser->vals );

## Results in

    Array(

        [title] => The title
        [intro] => Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. <strong>Ut enim ad minim veniam</strong>,
        [helpUrl] => http://google.com
        [bucket] => Array
            (
                [0] => Array
                    (
                        [title] => Create Your Own Reports
                        [body] => Click on the links below to generate your own reports based on competitive sets and date ranges of your choosing.
                        [linkUrl] => http://google.com
                        [linkLabel] => Link
                    )

                [1] => Array
                    (
                        [title] => Lorem ipsum dolor sit amet.
                        [body] => Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                        [linkUrl] => http://google.com
                        [linkLabel] => Hey Alex
                    )
            )

        [dashboardLink] => Array
            (
                [0] => Array
                    (
                        [linkUrl] => http://google.com
                        [linkLabel] => Link
                    )

                [1] => Array
                    (
                        [linkHref] => http://google.com
                        [linkLabel] => Link
                    )

            )

    )

## By default Parser uses Markdown to parse the values, but can be turned off with

    $parser =  new PostParser( $text, FALSE );

## Additionally, you can turn off for individual values using ! will keep the value from being marked down.

    body::! Dont mark me down man... _i like uderscores_

